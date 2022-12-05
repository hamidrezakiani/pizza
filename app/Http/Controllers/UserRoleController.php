<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAttachRoleRequest;
use App\Http\Requests\UserDetachRoleRequest;
use App\Http\Requests\UserRolesRequest;
use App\Http\Resources\RoleCollection;
use App\Lib\ResponseTemplate;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    use ResponseTemplate;
    /**
     * @OA\Get(

     *  path="/userRoles",

     * tags={"UserRole"},

     *  summary="get all user's roles",

     *  @OA\Parameter(name="user_id",

     *    in="query",

     *    required=true,

     *    description="",

     *    @OA\Schema(type="integer")

     *  ),

     *  @OA\Response(response="200",

     *    description="success,return array of role objects",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="active", type="boolean"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
    public function index(UserRolesRequest $request)
    {
        try{
            $user = User::find($request->user_id);
            $roles = $user->roles;
            $this->setData(new RoleCollection($roles));
        }catch(Exception $e)
        {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }

        return $this->response();

    }

    /**
     * @OA\Post(

     *  path="/userAttachRole",

     * tags={"UserRole"},

     *  summary="attach a role to user",

     *  @OA\Parameter(name="user_id",

     *    in="query",

     *    required=true,

     *    description="",

     *    @OA\Schema(type="integer")

     *  ),

     *  @OA\Parameter(name="role_id",

     *    in="query",

     *    required=true,

     *    description="",

     *    @OA\Schema(type="integer")

     *  ),

     *  @OA\Response(response="200",

     *    description="success",

     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
    public function attach(UserAttachRoleRequest $request)
    {
        try{
            $user = User::find($request->user_id);
            $user->roles()->syncWithoutDetaching($request->role_id);
        }catch(Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }
        return $this->response();
    }

    /**
     * @OA\Post(

     *  path="/userDetachRole",

     * tags={"UserRole"},

     *  summary="detach a role of user",

     *  @OA\Parameter(name="user_id",

     *    in="query",

     *    required=true,

     *    description="",

     *    @OA\Schema(type="integer")

     *  ),

     *  @OA\Parameter(name="role_id",

     *    in="query",

     *    required=true,

     *    description="",

     *    @OA\Schema(type="integer")

     *  ),

     *  @OA\Response(response="200",

     *    description="success",

     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
    public function detach(UserDetachRoleRequest $request)
    {
        try {
            $user = User::find($request->user_id);
            $user->roles()->detach($request->role_id);
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }
        return $this->response();
    }
}
