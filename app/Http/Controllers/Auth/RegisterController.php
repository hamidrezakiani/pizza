<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewVerificationCodeRequestAccepted;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\MobileVerificationRequest;
use App\Http\Requests\newVerificationCodeRequest;
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
    /**
     * @OA\Post(

     *  path="/register",

     * tags={"Authentication"},

     *  operationId="registerUser",

     *  summary="register new user",

     *  @OA\Parameter(name="firstName",

     *    in="query",

     *    required=true,

     *    @OA\Schema(
     *    type="string",
     *    maximum=30
     *    )

     *  ),

     *    @OA\Parameter(name="lastName",

     *    in="query",

     *    required=false,

     *    @OA\Schema(
     *    type="string",
     *    maximum=30,
     *    )

     *  ),

     *    @OA\Parameter(name="mobile",

     *    in="query",

     *    required=true,

     *    @OA\Schema(
     *    type="string",
     *    pattern= "^0[0-9]{10}$"
     *    )

     *  ),

     *  @OA\Parameter(name="password",

     *    in="query",

     *    required=true,

     *     @OA\Schema(
     *    type="string",
     *    minimum=8,
     *    )

     *  ),

     *  @OA\Response(response="200",

     *    description="success",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="firstName", type="string"),
     *        @OA\Property(property="lastName", type="string"),
     *        @OA\Property(property="mobileVerify", type="boolean"),
     *        @OA\Property(property="api_token", type="string"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
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
    /**
     * @OA\Post(

     *  path="/mobileVerification",

     * tags={"Authentication"},

     *  operationId="mobileVerification",

     *  summary="Mobile verification using SMS verification code",

     *    @OA\Parameter(name="mobile",

     *    in="query",

     *    required=true,

     *    @OA\Schema(
     *    type="string",
     *    pattern="^0[0-9]{10}$"
     *    )

     *  ),

     *  @OA\Parameter(name="code",

     *    in="query",

     *    required=true,

     *     @OA\Schema(
     *     type="string",
     *     pattern="^[0-9]{4}$"
     *    )

     *  ),

     *  @OA\Response(response="200",

     *    description="success",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="firstName", type="string"),
     *        @OA\Property(property="lastName", type="string"),
     *        @OA\Property(property="mobileVerify", type="boolean"),
     *        @OA\Property(property="api_token", type="string"),
     *     )
     *  ),

     *   @OA\Response(response="401",

     *    description="wrong verification code",

     * ),

     *   @OA\Response(response="422",

     *    description="validation errors",

     * )
     *  ),

     * )

     */
    public function mobileVerification(MobileVerificationRequest $request)
    {
        $verifyCode = SmsVerification::where('mobile',$request->mobile)
                    ->where('expired_at',null)
                    ->where('status','NOT_USED')->orderBy('created_at','ASC')->first();

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


    /**
     * @OA\Post(

     *  path="/newVerificationCode",

     * tags={"Authentication"},

     *  operationId="newVerificationCode",

     *  summary="send a new verification code after 2 minutes after the last verification code sent",

     *    @OA\Parameter(name="mobile",

     *    in="query",

     *    required=true,

     *    @OA\Schema(
     *    type="string",
     *    pattern="^0[0-9]{10}$"
     *    )

     *  ),

     *  @OA\Response(response="200",

     *    description="success and sent new sms verification code ",
     *
     *  ),

     *   @OA\Response(response="403",

     *    description="You will receive this response when you request again less than 2 minutes after the last request",

     * ),

     *   @OA\Response(response="422",

     *    description="validation errors",

     * )
     *  ),

     * )

     */
    public function newVerificationCode(newVerificationCodeRequest $request)
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
