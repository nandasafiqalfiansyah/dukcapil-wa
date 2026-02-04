<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class NlpLogController extends Controller
{
    /**
     * Display NLP logs interface.
     */
    public function index(Request $request)
    {
        $query = ChatMessage::where('role', 'bot')
            ->whereNotNull('intent')
            ->with('chatSession:id')
            ->orderBy('created_at', 'desc');

        // Filter by intent
        if ($request->filled('intent')) {
            $query->where('intent', $request->intent);
        }

        // Filter by confidence range
        if ($request->filled('min_confidence')) {
            $query->where('confidence', '>=', $request->min_confidence / 100);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        // Get unique intents for filter - cache for better performance
        $intents = cache()->remember('nlp_intents', 600, function () {
            return ChatMessage::where('role', 'bot')
                ->whereNotNull('intent')
                ->distinct()
                ->pluck('intent')
                ->sort();
        });

        return view('admin.nlp-logs.index', compact('logs', 'intents'));
    }

    /**
     * Get live NLP logs via AJAX.
     */
    public function live(Request $request)
    {
        $since = $request->query('since');
        
        $query = ChatMessage::where('role', 'bot')
            ->whereNotNull('intent')
            ->with('chatSession:id')
            ->orderBy('created_at', 'desc');

        if ($since) {
            $query->where('created_at', '>', $since);
        } else {
            $query->limit(50);
        }

        $logs = $query->get();

        return response()->json([
            'success' => true,
            'logs' => $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'message' => $log->message,
                    'intent' => $log->intent,
                    'confidence' => $log->confidence,
                    'session_id' => $log->chat_session_id,
                    'matched_pattern' => $log->metadata['matched_pattern'] ?? null,
                    'created_at' => $log->created_at->toISOString(),
                    'created_at_human' => $log->created_at->format('H:i:s'),
                ];
            }),
            'latest_timestamp' => $logs->isNotEmpty() ? $logs->first()->created_at->toISOString() : null,
        ]);
    }

    /**
     * Get NLP statistics.
     */
    public function statistics()
    {
        // Cache statistics for 5 minutes to improve performance
        $cacheKey = 'nlp_statistics';
        $cacheDuration = 300; // 5 minutes
        
        $statistics = cache()->remember($cacheKey, $cacheDuration, function () {
            // Use a single optimized query to get counts and averages
            $stats = ChatMessage::where('role', 'bot')
                ->whereNotNull('intent')
                ->selectRaw('
                    COUNT(*) as total_processed,
                    AVG(CASE WHEN confidence IS NOT NULL THEN confidence END) as avg_confidence,
                    SUM(CASE WHEN confidence < 0.5 THEN 1 ELSE 0 END) as low_confidence_count
                ')
                ->first();

            $intentDistribution = ChatMessage::where('role', 'bot')
                ->whereNotNull('intent')
                ->selectRaw('intent, COUNT(*) as count')
                ->groupBy('intent')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            return [
                'total_processed' => $stats->total_processed ?? 0,
                'average_confidence' => round(($stats->avg_confidence ?? 0) * 100, 2),
                'low_confidence_count' => $stats->low_confidence_count ?? 0,
                'intent_distribution' => $intentDistribution,
            ];
        });

        return response()->json([
            'success' => true,
            'statistics' => $statistics,
        ]);
    }
}
