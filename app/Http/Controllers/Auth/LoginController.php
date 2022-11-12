<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\ResponseTemplate;
class LoginController extends Controller
{
    use ResponseTemplate;
    /**
     * @OA\Post(

     *  path="/login",

     * tags={"Authentication"},

     *  operationId="loginUser",

     *  summary="Login to user account",

     *  @OA\Parameter(name="mobile",

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

     *    @OA\Schema(
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

     * *  @OA\Response(response="401",

     *    description="password or mobile is wrong",

     * )

     *  ),

     * )

     */
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->validate([
            'mobile' => ['required', 'regex:/(0)[0-9]{10}/'],
            'password' => ['required'],
        ]);

        if(auth('web')->attempt($credentials))
            $this->setData(new UserResource(User::where('mobile',$request->mobile)->first()));
        else{
            $this->setStatus(401);
            $this->setErrors(['auth' => ['موبایل یا پسورد اشتباه است']]);
        }

        return $this->response();
    }
}
