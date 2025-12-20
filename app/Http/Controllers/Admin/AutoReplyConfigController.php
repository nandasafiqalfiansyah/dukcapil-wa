<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoReplyConfig;
use Illuminate\Http\Request;

class AutoReplyConfigController extends Controller
{
    /**
     * Display a listing of auto-reply configurations.
     */
    public function index()
    {
        $autoReplies = AutoReplyConfig::orderBy('priority', 'desc')->paginate(20);

        return view('admin.auto-replies.index', compact('autoReplies'));
    }

    /**
     * Show the form for creating a new auto-reply.
     */
    public function create()
    {
        return view('admin.auto-replies.create');
    }

    /**
     * Store a newly created auto-reply.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trigger' => 'required|string|max:255|unique:auto_reply_configs,trigger',
            'response' => 'required|string',
            'priority' => 'required|integer|min:0|max:1000',
            'is_active' => 'boolean',
            'case_sensitive' => 'boolean',
        ]);

        AutoReplyConfig::create($validated);

        return redirect()->route('admin.auto-replies.index')
            ->with('success', 'Auto-reply configuration created successfully.');
    }

    /**
     * Display the specified auto-reply.
     */
    public function show(AutoReplyConfig $autoReply)
    {
        return view('admin.auto-replies.show', compact('autoReply'));
    }

    /**
     * Show the form for editing the specified auto-reply.
     */
    public function edit(AutoReplyConfig $autoReply)
    {
        return view('admin.auto-replies.edit', compact('autoReply'));
    }

    /**
     * Update the specified auto-reply.
     */
    public function update(Request $request, AutoReplyConfig $autoReply)
    {
        $validated = $request->validate([
            'trigger' => 'required|string|max:255|unique:auto_reply_configs,trigger,'.$autoReply->id,
            'response' => 'required|string',
            'priority' => 'required|integer|min:0|max:1000',
            'is_active' => 'boolean',
            'case_sensitive' => 'boolean',
        ]);

        $autoReply->update($validated);

        return redirect()->route('admin.auto-replies.index')
            ->with('success', 'Auto-reply configuration updated successfully.');
    }

    /**
     * Toggle the active status of an auto-reply.
     */
    public function toggleActive(AutoReplyConfig $autoReply)
    {
        $autoReply->update(['is_active' => ! $autoReply->is_active]);

        return back()->with('success', 'Auto-reply status toggled successfully.');
    }

    /**
     * Remove the specified auto-reply.
     */
    public function destroy(AutoReplyConfig $autoReply)
    {
        $autoReply->delete();

        return redirect()->route('admin.auto-replies.index')
            ->with('success', 'Auto-reply configuration deleted successfully.');
    }
}
