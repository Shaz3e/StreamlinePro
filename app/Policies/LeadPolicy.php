<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Lead;

class LeadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin)
    {
        if ($admin->can('lead.list')) {
            return true;
        }
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, Lead $lead)
    {
        if ($admin->can('lead.read')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin)
    {
        if ($admin->can('lead.create')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Lead $lead)
    {
        if ($admin->can('lead.update')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Lead $lead)
    {
        if ($admin->can('lead.delete')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, Lead $lead)
    {
        if ($admin->can('lead.restore')) {
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Lead $lead)
    {
        if ($admin->can('lead.force.delete')) {
            return true;
        }
    }
}