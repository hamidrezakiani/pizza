<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewVerificationCodeRequestAccepted;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\MobileVerificationRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Lib\ResponseTemplate;
use App\Models\SmsVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use ResponseTemplate;

    public function register(RegisterRequest $request)
    {
       $user = User::create([
            'firstName' => $request->firstName,
            'lastName'  => $request->lastName,
            'mobile'    => $request->mobile,
            'password'  => $request->password
        ]);
        if($user)
        {
            event(new UserRegistered($user));
            $this->setData(new UserResource($user));
            return $this->response();
        }
        else
        {
            $this->setStatus(422);
            return $this->response();
        }
    }

    public function mobileVerification(MobileVerificationRequest $request)
    {
        $verifyCode = SmsVerification::where('mobile',$request->mobile)
                    ->where('expired_at',null)
                    ->where('status','NOT_USED')->first();

        if($verifyCode && $verifyCode->created_at->gt(Carbon::now()->subMinute(2)))
        {
            $verifyCode->expired_at = Carbon::now();
            if($verifyCode->code == $request->code)
            {
               $verifyCode->status = 'VERIFIED';
               $user = User::where('mobile',$request->mobile)->first();
               $this->setData(new UserResource($user));
            }
            else
            {
                $verifyCode->status = 'FAILED_ATTEMPT';
                $this->setErrors(['code' => ['کد وارد شده صحیح نمیباشد']]);
                $this->setStatus(401);
            }
            $verifyCode->save();
            return $this->response();
        }
        else
        {
            if($verifyCode)
            {
                $verifyCode->status = 'TIME_LEFT';
                $verifyCode->expired_at = Carbon::now();
                $verifyCode->save();
            }
            $this->setErrors(['code' => ['کد تایید منقضی شده است']]);
            $this->setStatus(403);
            return $this->response();
        }
    }

    public function newVerificationCode(Request $request)
    {
        $user = User::where('mobile',$request->mobile)->first();
        $activeVerificationCode = $user->smsVerifications()->where('created_at','>',Carbon::now()->subMinutes(2))->first();
        if($activeVerificationCode)
        {
           $this->setStatus(403);
           $this->setErrors(['code' => ['ارسال کد جدید قبل از 2 دقیقه مقدور نمیباشد']]);
           return $this->response();
        }

        event(new NewVerificationCodeRequestAccepted($user));

        return $this->response();
    }
}
