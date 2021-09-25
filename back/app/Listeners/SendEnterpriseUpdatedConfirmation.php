<?php

namespace App\Listeners;

use App\Events\enterpriseUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEnterpriseUpdatedConfirmation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  enterpriseUpdated  $event
     * @return void
     */
    public function handle(enterpriseUpdated $event)
    {
        //
        
    }
}
