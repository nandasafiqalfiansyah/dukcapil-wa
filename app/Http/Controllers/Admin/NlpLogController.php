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
            ->with('chatSession')
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

        // Get unique intents for filter
        $intents = ChatMessage::where('role', 'bot')
            ->whereNotNull('intent')
            ->distinct()
            ->pluck('intent')
            ->sort();

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
            ->with('chatSession')
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
        $totalProcessed = ChatMessage::where('role', 'bot')
            ->whereNotNull('intent')
            ->count();

        $avgConfidence = ChatMessage::where('role', 'bot')
            ->whereNotNull('intent')
            ->whereNotNull('confidence')
            ->avg('confidence');

        $intentDistribution = ChatMessage::where('role', 'bot')
            ->whereNotNull('intent')
            ->selectRaw('intent, COUNT(*) as count')
            ->groupBy('intent')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $lowConfidenceCount = ChatMessage::where('role', 'bot')
            ->whereNotNull('confidence')
            ->where('confidence', '<', 0.5)
            ->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'total_processed' => $totalProcessed,
                'average_confidence' => round(($avgConfidence ?? 0) * 100, 2),
                'low_confidence_count' => $lowConfidenceCount,
                'intent_distribution' => $intentDistribution,
            ],
        ]);
    }
}
