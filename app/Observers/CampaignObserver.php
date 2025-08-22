<?php

namespace App\Observers;

use App\Models\Campaign;
use App\Http\Controllers\Admin\DashboardController;

class CampaignObserver
{
    /**
     * Handle the Campaign "created" event.
     */
    public function created(Campaign $campaign): void
    {
        // Trigger dashboard broadcast when new campaign is created
        $dashboardController = new DashboardController();
        $dashboardController->broadcastDashboardUpdate('campaign_created');
        $dashboardController->broadcastActivitiesUpdate();
    }

    /**
     * Handle the Campaign "updated" event.
     */
    public function updated(Campaign $campaign): void
    {
        // Trigger dashboard broadcast when campaign status is updated
        if ($campaign->isDirty('status')) {
            $dashboardController = new DashboardController();
            $dashboardController->broadcastDashboardUpdate('campaign_updated');
            $dashboardController->broadcastActivitiesUpdate();
        }
    }
}
