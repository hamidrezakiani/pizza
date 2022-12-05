<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleActionsRequest;
use App\Http\Requests\RoleAttachActionRequest;
use App\Http\Requests\RoleDetachActionRequest;
use App\Http\Resources\ActionCollection;
use App\Lib\ResponseTemplate;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleActionController extends Controller
{
    use ResponseTemplate;

    /**
     * @OA\Get(

     *  path="/roleActions",

     * tags={"RoleAction"},

     *  summary="get all role's actions",

     *  @OA\Parameter(name="role_id",

     *    in="query",

     *    required=true,

     *    description="",

     *    @OA\Schema(type="integer")

     *  ),

     *  @OA\Response(response="200",

     *    description="success,return array of action objects",
    *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
    public function index(RoleActionsRequest $request)
    {
        try {
            $role = Role::find($request->role_id);
            $actions = $role->actions;
            $this->setData(new ActionCollection($actions));
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }

        return $this->response();
    }

    /**
     * @OA\Post(

     *  path="/roleAttachAction",

     * tags={"RoleAction"},

     *  summary="attach a action to role",

     *  @OA\Parameter(name="role_id",

     *    in="query",

     *    required=true,

     *    description="",

     *    @OA\Schema(type="integer")

     *  ),

     *  @OA\Parameter(name="action_id",

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
    public function attach(RoleAttachActionRequest $request)
    {
        try {
            $role = Role::find($request->role_id);
            $role->actions()->syncWithoutDetaching($request->action_id);
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }
        return $this->response();
    }

    /**
     * @OA\Post(

     *  path="/roleDetachAction",

     * tags={"RoleAction"},

     *  summary="detach a action of role",

     *  @OA\Parameter(name="role_id",

     *    in="query",

     *    required=true,

     *    description="",

     *    @OA\Schema(type="integer")

     *  ),

     *  @OA\Parameter(name="action_id",

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
    public function detach(RoleDetachActionRequest $request)
    {
        try {
            $role = Role::find($request->role_id);
            $role->actions()->detach($request->action_id);
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }
        return $this->response();
    }
}
