<?php

namespace App\Observers;

use App\Models\Doctor;
use App\Http\Controllers\Admin\DashboardController;

class DoctorObserver
{
    /**
     * Handle the Doctor "created" event.
     */
    public function created(Doctor $doctor): void
    {
        // Trigger dashboard broadcast when new doctor registers
        $dashboardController = new DashboardController();
        $dashboardController->broadcastDashboardUpdate('doctor_registered');
    }

    /**
     * Handle the Doctor "updated" event.
     */
    public function updated(Doctor $doctor): void
    {
        // Trigger dashboard broadcast when doctor verification status changes
        if ($doctor->isDirty('email_verified_at')) {
            $dashboardController = new DashboardController();
            $dashboardController->broadcastDashboardUpdate('doctor_verified');
        }
    }
}
