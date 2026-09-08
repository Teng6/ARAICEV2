<?php

namespace App\Providers;

use App\Models\Alert;
use App\Models\Disease;
use App\Models\Outbreak;
use App\Models\Report;
use App\Models\RiceVariety;
use App\Models\Scan;
use App\Models\Treatment;
use App\Policies\AlertPolicy;
use App\Policies\OutbreakPolicy;
use App\Policies\ReferenceDataPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ScanPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Scan::class => ScanPolicy::class,
        Outbreak::class => OutbreakPolicy::class,
        Alert::class => AlertPolicy::class,
        Report::class => ReportPolicy::class,

        // Three models, one shared policy — see ReferenceDataPolicy's
        // docblock for why.
        Disease::class => ReferenceDataPolicy::class,
        Treatment::class => ReferenceDataPolicy::class,
        RiceVariety::class => ReferenceDataPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
