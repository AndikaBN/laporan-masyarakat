<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Report;
use App\Policies\UserPolicy;
use App\Policies\ReportPolicy;
use Illuminate\Support\ServiceProvider;

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
        $this->registerPolicies();
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        \Illuminate\Support\Facades\Gate::policy(User::class, UserPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(Report::class, ReportPolicy::class);
    }
}
