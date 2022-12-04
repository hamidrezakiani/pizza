<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Lib\ResponseTemplate;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    use ResponseTemplate;
    /**
     * @OA\Get(

     *  path="/users",

     * tags={"User"},

     *  operationId="getUsers",

     *  summary="get users",

     *  @OA\Response(response="200",

     *    description="success,return array of users",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="firstName", type="string"),
     *        @OA\Property(property="lastName", type="string"),
     *        @OA\Property(property="mobile", type="string"),
     *        @OA\Property(property="mobileVerify", type="boolean"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
    public function index(IndexUserRequest $request)
    {
        $users =  User::all();
        $this->setData(new UserCollection($users));
        return $this->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try{
            $user = User::findOrFail($id);
            Gate::authorize('update', $user);
            $user =  $user->update([
                'firstName' => $request->firstName ?? $user->firstName,
                'lastName'  => $request->lastName  ?? $user->lastName
            ]);

            $this->setData(new UserResource(User::find($id)));
        }catch(Exception $e){
           if($e instanceof ModelNotFoundException)
           {
             $this->setErrors(['message' => ['رکوردی یافت نشد.']]);
             $this->setStatus(404);
           }
           else
           {
              $this->setErrors(['message' => ['خطای سیستمی']]);
              $this->setStatus(500);
           }
        }
        return $this->response();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
