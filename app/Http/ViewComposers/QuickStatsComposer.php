<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Campaign;
use App\Models\CampaignSponsor;
use App\Models\PatientRegistration;

class QuickStatsComposer
{
    public function compose(View $view)
    {
        $view->with('totalCampaigns', Campaign::count());
        $view->with('totalSponsors', CampaignSponsor::count());
        $view->with('totalPatients', PatientRegistration::count());
    }
}
