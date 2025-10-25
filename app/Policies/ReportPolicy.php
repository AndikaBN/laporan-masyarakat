<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Determine if user can view a report.
     */
    public function view(User $user, Report $report): bool
    {
        // Super admin can view all reports
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Agency admin can view reports from their agency
        if ($user->isAgencyAdmin()) {
            return $report->category?->agency_id === $user->agency_id;
        }

        // User can view their own reports
        return $report->user_id === $user->id;
    }

    /**
     * Determine if user can create a report.
     */
    public function create(User $user): bool
    {
        // Only regular users can create reports
        return $user->role === 'user';
    }

    /**
     * Determine if user can update report status.
     */
    public function updateStatus(User $user, Report $report): bool
    {
        // Super admin can update any report
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Agency admin can update reports from their agency
        if ($user->isAgencyAdmin()) {
            return $report->category?->agency_id === $user->agency_id;
        }

        return false;
    }
}
