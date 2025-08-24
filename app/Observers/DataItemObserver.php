<?php

namespace App\Observers;

use App\Jobs\SyncDataJob;
use App\Models\DataItem;
use Illuminate\Support\Facades\Log;

class DataItemObserver
{
    /**
     * Handle the DataItem "created" event.
     */
    public function created(DataItem $dataItem): void
    {
        SyncDataJob::dispatch();
    }

    /**
     * Handle the DataItem "updated" event.
     */
    public function updated(DataItem $dataItem): void
    {
        SyncDataJob::dispatch();
    }

    /**
     * Handle the DataItem "deleted" event.
     */
    public function deleted(DataItem $dataItem): void
    {
        SyncDataJob::dispatch();
    }

    /**
     * Handle the DataItem "restored" event.
     */
    public function restored(DataItem $dataItem): void
    {
        //
    }

    /**
     * Handle the DataItem "force deleted" event.
     */
    public function forceDeleted(DataItem $dataItem): void
    {
        SyncDataJob::dispatch();
    }
}
