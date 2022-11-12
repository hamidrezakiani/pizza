<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyRoleRequest;
use App\Http\Requests\IndexRoleRequest;
use App\Http\Requests\ShowRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Lib\ResponseTemplate;
use App\Models\Role;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ResponseTemplate;
    /**
     * @OA\Get(

     *  path="/roles",

     * tags={"Role"},

     *  operationId="getRoles",

     *  summary="get roles",

     *  @OA\Response(response="200",

     *    description="success,return array of roles",
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
    public function index(IndexRoleRequest $request)
    {
        try{
            $roles = Role::all();
            $this->setData(new RoleCollection($roles));

        }catch (Exception $e) {
            $this->setErrors(['message' => 'خطای سیستمی']);
            $this->setStatus(500);
        }
         return $this->response();
    }

    /**
     * @OA\Post(

     *  path="/roles",

     * tags={"Role"},

     *  summary="store a role",

     *  @OA\Parameter(name="name",

     *    in="query",

     *    required=true,

     *    description="role name",

     *    @OA\Schema(type="string")

     *  ),

     *  @OA\Parameter(name="active",

     *    in="query",

     *    required=false,

     *    description="Whether a role is active or not",

     *    @OA\Schema(type="boolean")

     *  ),

     *  @OA\Response(response="200",

     *    description="success,return object of role",
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
    public function store(StoreRoleRequest $request)
    {
        try
        {
            $role = Role::create(['name' => $request->name]);
            $this->setData(new RoleResource($role));

        }catch(Exception $e){
            $this->setErrors(['message' => $e->getMessage()]);
            $this->setStatus(500);
        }
        return $this->response();
    }

    /**
     * @OA\Get(

     *  path="/roles/{role_id}",

     * tags={"Role"},

     *  summary="get a role by id",

     *  @OA\Response(response="200",

     *    description="success,return object of role",
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
    public function show(ShowRoleRequest $request,$id)
    {
        try
        {
          $role = Role::findOrFail($id);
          $this->setData(new RoleResource($role));

        }catch(Exception $e){
           if($e instanceof ModelNotFoundException)
           {
             $this->setErrors(['message' => 'رکوردی یافت نشد.']);
             $this->setStatus(404);
           }
           else
           {
              $this->setErrors(['message' => 'خطای سیستمی']);
              $this->setStatus(500);
           }
        }

        return $this->response();
    }

    /**
     * @OA\Put(

     *  path="/roles/{role_id}",

     * tags={"Role"},

     *  summary="update a role",

     *  @OA\Parameter(name="name",

     *    in="query",

     *    required=true,

     *    description="role name",

     *    @OA\Schema(type="string")

     *  ),

     *  @OA\Parameter(name="active",

     *    in="query",

     *    required=false,

     *    description="Whether a role is active or not",

     *    @OA\Schema(type="boolean")

     *  ),

     *  @OA\Response(response="200",

     *    description="success",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="active", type="boolean"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation error",

     * )

     *  ),

     * )

     */
    public function update(UpdateRoleRequest $request, $id)
    {
        try{
          $role = Role::findOrFail($id);
          $request->name ?? $role->name = $request->name;
          $request->active ?? $role->active = $request->active;
          $role->save();
          $this->setData(new RoleResource($role));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $this->setErrors(['message' => 'رکوردی یافت نشد.']);
                $this->setStatus(404);
            } else {
                $this->setErrors(['message' => 'خطای سیستمی']);
                $this->setStatus(500);
            }
        }

        return $this->response();

    }

    /**
     * @OA\Delete(

     *  path="/roles/{role_id}",

     * tags={"Role"},

     *  summary="delete a role",

     *  @OA\Response(response="200",

     *    description="success",
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation error",

     * )

     *  ),

     * )

     */
    public function destroy(DestroyRoleRequest $request,$id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $this->setErrors(['message' => 'چنین رکوردی وجود ندارد.']);
                $this->setStatus(404);
            } else {
                $this->setErrors(['message' => 'خطای سیستمی']);
                $this->setStatus(500);
            }
        }

        return $this->response();
    }
}
