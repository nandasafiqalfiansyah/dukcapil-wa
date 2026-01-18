<?php

namespace App\Observers;

use App\Models\CsTrainingData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CsTrainingDataObserver
{
    /**
     * Handle the CsTrainingData "created" event.
     */
    public function created(CsTrainingData $trainingData): void
    {
        $this->clearTrainingDataCache();
        
        Log::info('Training data created, cache cleared', [
            'id' => $trainingData->id,
            'intent' => $trainingData->intent,
        ]);
    }

    /**
     * Handle the CsTrainingData "updated" event.
     */
    public function updated(CsTrainingData $trainingData): void
    {
        $this->clearTrainingDataCache();
        
        Log::info('Training data updated, cache cleared', [
            'id' => $trainingData->id,
            'intent' => $trainingData->intent,
        ]);
    }

    /**
     * Handle the CsTrainingData "deleted" event.
     */
    public function deleted(CsTrainingData $trainingData): void
    {
        $this->clearTrainingDataCache();
        
        Log::info('Training data deleted, cache cleared', [
            'id' => $trainingData->id,
            'intent' => $trainingData->intent,
        ]);
    }

    /**
     * Handle the CsTrainingData "restored" event.
     */
    public function restored(CsTrainingData $trainingData): void
    {
        $this->clearTrainingDataCache();
        
        Log::info('Training data restored, cache cleared', [
            'id' => $trainingData->id,
            'intent' => $trainingData->intent,
        ]);
    }

    /**
     * Clear training data cache.
     */
    protected function clearTrainingDataCache(): void
    {
        Cache::forget('cs_training_data_active');
    }
}
