<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\DoctorPayment;
use App\Models\PatientPayment;
use App\Models\Campaign;
use App\Models\Doctor;
use App\Models\User;
use App\Observers\DoctorPaymentObserver;
use App\Observers\PatientPaymentObserver;
use App\Observers\CampaignObserver;
use App\Observers\DoctorObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\QuickStatsComposer;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('admin.components.sidebar-link', 'admin.sidebarlink');
          View::composer('*', QuickStatsComposer::class);
        // Register Model Observers for Real-time Broadcasting
        DoctorPayment::observe(DoctorPaymentObserver::class);
        PatientPayment::observe(PatientPaymentObserver::class);
        Campaign::observe(CampaignObserver::class);
        Doctor::observe(DoctorObserver::class);
        User::observe(UserObserver::class);
    }
}
