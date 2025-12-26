<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CsTrainingData;
use Illuminate\Http\Request;

class ChatConfigController extends Controller
{
    /**
     * Display chat configuration interface.
     */
    public function index()
    {
        $trainingData = CsTrainingData::orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.chat-config.index', compact('trainingData'));
    }

    /**
     * Show form for creating new training data.
     */
    public function create()
    {
        return view('admin.chat-config.create');
    }

    /**
     * Store new training data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'intent' => 'required|string|max:255',
            'pattern' => 'required|string|max:500',
            'response' => 'required|string|max:2000',
            'keywords' => 'nullable|string',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['keywords'] = $this->processKeywords($validated['keywords'] ?? '');
        $validated['is_active'] = $request->has('is_active');
        $validated['priority'] = $validated['priority'] ?? 50;

        CsTrainingData::create($validated);

        return redirect()->route('admin.chat-config.index')
            ->with('success', 'Training data created successfully.');
    }

    /**
     * Show form for editing training data.
     */
    public function edit(CsTrainingData $chatConfig)
    {
        return view('admin.chat-config.edit', compact('chatConfig'));
    }

    /**
     * Update training data.
     */
    public function update(Request $request, CsTrainingData $chatConfig)
    {
        $validated = $request->validate([
            'intent' => 'required|string|max:255',
            'pattern' => 'required|string|max:500',
            'response' => 'required|string|max:2000',
            'keywords' => 'nullable|string',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['keywords'] = $this->processKeywords($validated['keywords'] ?? '');
        $validated['is_active'] = $request->has('is_active');

        $chatConfig->update($validated);

        return redirect()->route('admin.chat-config.index')
            ->with('success', 'Training data updated successfully.');
    }

    /**
     * Delete training data.
     */
    public function destroy(CsTrainingData $chatConfig)
    {
        $chatConfig->delete();

        return redirect()->route('admin.chat-config.index')
            ->with('success', 'Training data deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(CsTrainingData $chatConfig)
    {
        $chatConfig->update([
            'is_active' => !$chatConfig->is_active,
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $chatConfig->is_active,
        ]);
    }

    /**
     * Process keywords string into array.
     */
    private function processKeywords(string $keywords): array
    {
        if (empty($keywords)) {
            return [];
        }

        return array_map('trim', explode(',', $keywords));
    }
}
