<?php

namespace App\Observers;

use App\Models\AutoReplyConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AutoReplyConfigObserver
{
    /**
     * Handle the AutoReplyConfig "created" event.
     */
    public function created(AutoReplyConfig $autoReply): void
    {
        $this->clearAutoReplyCache();
        
        Log::info('Auto-reply config created, cache cleared', [
            'id' => $autoReply->id,
            'trigger' => $autoReply->trigger,
        ]);
    }

    /**
     * Handle the AutoReplyConfig "updated" event.
     */
    public function updated(AutoReplyConfig $autoReply): void
    {
        $this->clearAutoReplyCache();
        
        Log::info('Auto-reply config updated, cache cleared', [
            'id' => $autoReply->id,
            'trigger' => $autoReply->trigger,
        ]);
    }

    /**
     * Handle the AutoReplyConfig "deleted" event.
     */
    public function deleted(AutoReplyConfig $autoReply): void
    {
        $this->clearAutoReplyCache();
        
        Log::info('Auto-reply config deleted, cache cleared', [
            'id' => $autoReply->id,
            'trigger' => $autoReply->trigger,
        ]);
    }

    /**
     * Handle the AutoReplyConfig "restored" event.
     */
    public function restored(AutoReplyConfig $autoReply): void
    {
        $this->clearAutoReplyCache();
        
        Log::info('Auto-reply config restored, cache cleared', [
            'id' => $autoReply->id,
            'trigger' => $autoReply->trigger,
        ]);
    }

    /**
     * Clear auto-reply config cache.
     */
    protected function clearAutoReplyCache(): void
    {
        Cache::forget('auto_reply_configs_active');
    }
}
