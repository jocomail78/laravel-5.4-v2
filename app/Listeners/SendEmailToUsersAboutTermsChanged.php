<?php

namespace App\Listeners;

use App\Events\TermsChanged;
use App\Jobs\SendTermsChangedEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TermsChangedEmail;

class SendEmailToUsersAboutTermsChanged
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
     * @param  TermsChanged  $event
     * @return void
     */
    public function handle(TermsChanged $event)
    {
        dispatch(new SendTermsChangedEmail());
    }
}
