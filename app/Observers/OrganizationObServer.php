<?php

namespace App\Observers;

use App\Models\Organization;

class OrganizationObServer
{
    /**
     * Handle the organization "created" event.
     *
     * @param Organization $organization
     * @return void
     */
    public function created(Organization $organization)
    {

        //

    }

    /**
     * Handle the organization "updated" event.
     *
     * @param Organization $organization
     * @return void
     */
    public function updated(Organization $organization)
    {
        //
    }

    /**
     * Handle the organization "deleted" event.
     *
     * @param Organization $organization
     * @return void
     */
    public function deleted(Organization $organization)
    {
        //
    }

    /**
     * Handle the organization "restored" event.
     *
     * @param Organization $organization
     * @return void
     */
    public function restored(Organization $organization)
    {
        //
    }

    /**
     * Handle the organization "force deleted" event.
     *
     * @param Organization $organization
     * @return void
     */
    public function forceDeleted(Organization $organization)
    {
        //
    }
}
