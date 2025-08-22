<?php

namespace App\Observers;

use App\Models\PatientPayment;
use App\Http\Controllers\Admin\DashboardController;

class PatientPaymentObserver
{
    /**
     * Handle the PatientPayment "created" event.
     */
    public function created(PatientPayment $patientPayment): void
    {
        // Trigger dashboard broadcast when new patient payment is created
        $dashboardController = new DashboardController();
        $dashboardController->broadcastDashboardUpdate('patient_payment_created');
        $dashboardController->broadcastChartUpdate();
        $dashboardController->broadcastActivitiesUpdate();
    }

    /**
     * Handle the PatientPayment "updated" event.
     */
    public function updated(PatientPayment $patientPayment): void
    {
        // Trigger dashboard broadcast when patient payment status is updated
        if ($patientPayment->isDirty('payment_status')) {
            $dashboardController = new DashboardController();
            $dashboardController->broadcastDashboardUpdate('patient_payment_updated');
            $dashboardController->broadcastChartUpdate();
            $dashboardController->broadcastActivitiesUpdate();
        }
    }
}
