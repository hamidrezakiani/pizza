<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroySeasoningRequest;
use App\Http\Requests\IndexSeasoningRequest;
use App\Http\Requests\ShowSeasoningRequest;
use App\Http\Requests\StoreSeasoningRequest;
use App\Http\Requests\UpdateSeasoningRequest;
use App\Http\Resources\SeasoningCollection;
use App\Http\Resources\SeasoningResource;
use App\Lib\ResponseTemplate;
use App\Models\Seasoning;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SeasoningController extends Controller
{
    use ResponseTemplate;
    /**
     * @OA\Get(

     *  path="/seasoning",

     * tags={"Seasoning"},

     *  operationId="getSeasonings",

     *  summary="get seasonings",

     *  @OA\Response(response="200",

     *    description="success,return array of seasonings",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="product_id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
    public function index(IndexSeasoningRequest $request)
    {
        try {
            $seasoning = Seasoning::where('product_id',$request->product_id)->get();
            $this->setData(new SeasoningCollection($seasoning));
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }
        return $this->response();
    }

    /**
     * @OA\Post(

     *  path="/seasonings",

     * tags={"Seasoning"},

     *  summary="store a seasoning",

     *  @OA\Parameter(name="name",

     *    in="query",

     *    required=true,

     *    description="product name",

     *    @OA\Schema(type="string")

     *  ),

     *   @OA\Parameter(name="product_id",

     *    in="query",

     *    required=true,

     *    description="select category for product",

     *    @OA\Schema(type="string")

     *  ),

     *  @OA\Response(response="200",

     *    description="success,return object of product",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="product_id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
    public function store(StoreSeasoningRequest $request)
    {
        try {
            $seasoning = Seasoning::create([
                'name' => $request->name,
                'product_id' => $request->product_id,
            ]);
            $this->setData(new SeasoningResource($seasoning));
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }
        return $this->response();
    }

    /**
     * @OA\Get(

     *  path="/seasonings/{seasoning_id}",

     * tags={"Seasoning"},

     *  summary="get a seasoning by id",

     *  @OA\Response(response="200",

     *    description="success,return object of seasoning",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="product_id", type="integer"),
     *        @OA\Property(property="name", type="string")
     *
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),
     */
    public function show(ShowSeasoningRequest $request, $id)
    {
        try {
            $role = Seasoning::findOrFail($id);
            $this->setData(new SeasoningResource($role));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $this->setErrors(['message' => ['رکوردی یافت نشد.']]);
                $this->setStatus(404);
            } else {
                $this->setErrors(['message' => ['خطای سیستمی']]);
                $this->setStatus(500);
            }
        }

        return $this->response();
    }

    /**
     * @OA\Put(

     *  path="/seasonings/{seasoning_id}",

     * tags={"Seasoning"},

     *  summary="update a seasoning",

     *  @OA\Parameter(name="product_id",

     *    in="query",

     *    required=true,

     *    description="product id",

     *    @OA\Schema(type="integer")

     *  ),

     * *  @OA\Parameter(name="name",

     *    in="query",

     *    required=true,

     *    description="role name",

     *    @OA\Schema(type="string")

     *  ),

     *  @OA\Response(response="200",

     *    description="success",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="product_id", type="string"),
     *        @OA\Property(property="name", type="string"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation error",

     * )

     *  ),

     * )

     */
    public function update(UpdateSeasoningRequest $request, $id)
    {
        try {
            $seasoning = Seasoning::findOrFail($id);
            $seasoning->update([
                'product_id' => $request->product_id ?? $$seasoning->product_id,
                'name'      => $request->name ?? $seasoning->name,
            ]);
            $this->setData(new SeasoningResource($seasoning));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $this->setErrors(['message' => ['رکوردی یافت نشد.']]);
                $this->setStatus(404);
            } else {
                $this->setErrors(['message' => ['خطای سیستمی']]);
                $this->setStatus(500);
            }
        }

        return $this->response();
    }

    /**
     * @OA\Delete(

     *  path="/seasoning/{seasoning_id}",

     * tags={"Seasoning"},

     *  summary="delete a seasoning",

     *  @OA\Response(response="200",

     *    description="success",
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation error",

     * )

     *  ),

     * )

     */
    public function destroy(DestroySeasoningRequest $request, $id)
    {
        try {
            $seasoning = Seasoning::findOrFail($id);
            $seasoning->delete();
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $this->setErrors(['message' => ['چنین رکوردی وجود ندارد.']]);
                $this->setStatus(404);
            } else {
                $this->setErrors(['message' => ['خطای سیستمی']]);
                $this->setStatus(500);
            }
        }

        return $this->response();
    }
}
