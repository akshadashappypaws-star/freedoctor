<?php

namespace App\Observers;

use App\Models\DoctorPayment;
use App\Http\Controllers\Admin\DashboardController;

class DoctorPaymentObserver
{
    /**
     * Handle the DoctorPayment "created" event.
     */
    public function created(DoctorPayment $doctorPayment): void
    {
        // Trigger dashboard broadcast when new doctor payment is created
        $dashboardController = new DashboardController();
        $dashboardController->broadcastDashboardUpdate('doctor_payment_created');
        $dashboardController->broadcastChartUpdate();
        $dashboardController->broadcastActivitiesUpdate();
    }

    /**
     * Handle the DoctorPayment "updated" event.
     */
    public function updated(DoctorPayment $doctorPayment): void
    {
        // Trigger dashboard broadcast when doctor payment status is updated
        if ($doctorPayment->isDirty('payment_status')) {
            $dashboardController = new DashboardController();
            $dashboardController->broadcastDashboardUpdate('doctor_payment_updated');
            $dashboardController->broadcastChartUpdate();
            $dashboardController->broadcastActivitiesUpdate();
        }
    }
}
