<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\SmsVerification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Cryptommer\Smsir\Smsir;

class SendVerificationSms
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
     * @param  \App\Events\UserRegistered  $event
     * @return void
     */
    public function handle($event)
    {
        $user =  $event->user;
        $code = rand(1000, 9999);
        $user->smsVerifications()->where('status','NOT_USED')->update(['status','TILE_LEFT']);
        $user->smsVerifications()->create([
            'mobile' => $user->mobile,
            'code' => $code,
        ]);

        $send = Smsir::Send();
        $parameter = new \Cryptommer\Smsir\Objects\Parameters('Code', $code);
        $parameters = array($parameter);
        $send->Verify($user->mobile, 100000, $parameters);
    }
}
