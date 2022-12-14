<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyProductRequest;
use App\Http\Requests\IndexProductRequest;
use App\Http\Requests\ShowProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Lib\ResponseTemplate;
use App\Models\Product;
use App\Models\Seasoning;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseTemplate;
    /**
     * @OA\Get(

     *  path="/products",

     * tags={"Product"},

     *  operationId="getProducts",

     *  summary="get products",

     *  @OA\Response(response="200",

     *    description="success,return array of products",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="category_id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="exist", type="boolean"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
    public function index(IndexProductRequest $request)
    {
        try {
            $products = Product::all();
            $this->setData(new ProductCollection($products));
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }
        return $this->response();
    }

    /**
     * @OA\Post(

     *  path="/products",

     * tags={"Product"},

     *  summary="store a product",

     *  @OA\Parameter(name="name",

     *    in="query",

     *    required=true,

     *    description="product name",

     *    @OA\Schema(type="string")

     *  ),

     *   @OA\Parameter(name="category_id",

     *    in="query",

     *    required=true,

     *    description="select category for product",

     *    @OA\Schema(type="string")

     *  ),

     *  @OA\Parameter(name="exist",

     *    in="query",

     *    required=false,

     *    description="Whether a product is exist or not(true by default)",

     *    @OA\Schema(type="boolean")

     *  ),

     *  @OA\Response(response="200",

     *    description="success,return object of product",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="category_id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="exist", type="boolean"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),

     * )

     */
    public function store(StoreProductRequest $request)
    {
        try
        {
            $product = Product::create(['name' => $request->name,
                                     'category_id' => $request->category_id,
                                     'exist'       => $request->exist ?? 1]);
            $this->setData(new ProductResource($product));
        }catch(Exception $e){
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }
        return $this->response();
    }

    /**
     * @OA\Get(

     *  path="/products/{product_id}",

     * tags={"Product"},

     *  summary="get a product by id",

     *  @OA\Response(response="200",

     *    description="success,return object of seasoning",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="category_id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="exist", type="boolean"),
     *
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation errors",

     * )

     *  ),
     */
    public function show(ShowProductRequest $request, $id)
    {
        try {
            $role = Product::findOrFail($id);
            $this->setData(new ProductResource($role));
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

     *  path="/products/{product_id}",

     * tags={"Product"},

     *  summary="update a product",

     *  @OA\Parameter(name="category_id",

     *    in="query",

     *    required=true,

     *    description="category id",

     *    @OA\Schema(type="integer")

     *  ),

     * *  @OA\Parameter(name="name",

     *    in="query",

     *    required=true,

     *    description="role name",

     *    @OA\Schema(type="string")

     *  ),

     *  @OA\Parameter(name="exist",

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
     *        @OA\Property(property="category_id", type="string"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="exist", type="boolean"),
     *     )
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation error",

     * )

     *  ),

     * )

     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update([
                'category_id' => $request->category_id ?? $product->category_id,
                'name'      => $request->name ?? $product->name,
                'exist'    => $request->exist ?? $product->exist,
            ]);
            $this->setData(new ProductResource($product));
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

     *  path="/product/{product_id}",

     * tags={"Product"},

     *  summary="delete a product",

     *  @OA\Response(response="200",

     *    description="success",
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation error",

     * )

     *  ),

     * )

     */
    public function destroy(DestroyProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->seasonings()->delete();
            $product->delete();
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
