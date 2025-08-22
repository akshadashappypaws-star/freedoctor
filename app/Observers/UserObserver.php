<?php

namespace App\Observers;

use App\Models\User;
use App\Http\Controllers\Admin\DashboardController;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Trigger dashboard broadcast when new user registers
        $dashboardController = new DashboardController();
        $dashboardController->broadcastDashboardUpdate('user_registered');
    }
}
