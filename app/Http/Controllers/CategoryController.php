<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Lib\ResponseTemplate;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use ResponseTemplate;
    /**
     * @OA\Get(

     *  path="/categories",

     * tags={"Category"},

     *  operationId="getCategories",

     *  summary="get categories",

     *  @OA\Parameter(name="flag",

     *    in="query",

     *    required=true,

     *    description="flag <b>all</b> for get all categories. flag <b>active</b> for get active categories.",

     *    @OA\Schema(
     *   type="string",
     *   default="all",
     *   enum={"all","active"}
     *   )

     *  ),
     *  @OA\Response(response="200",

     *    description="success,return array of category objects",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="parent_id", type="integer"),
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
    public function index(Request $request)
    {
       try{
        switch ($request->flag) {
            case 'all':
                $categories = Category::all();
                break;
            case 'active':
                $categories = Category::all()->active()->get();
                break;
            default:
                $categories = Category::all();
                break;
        }
        $this->setData(new CategoryCollection($categories));
       }catch(Exception $e){
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
       }
       return $this->response();
    }

    /**
     * @OA\Post(

     *  path="/categories",

     * tags={"Category"},

     *  summary="store a category",

     *  @OA\Parameter(name="Authorization",

     *    in="header",

     *    required=true,

     *    description="bearer token example : 'bearer skfjhskfhksfhkshfkshfkshfkshfkshfse636525xv3535353'",

     *    @OA\Schema(type="string")

     *  ),

     *  @OA\Parameter(name="parent_id",

     *    in="query",

     *    required=false,

     *    description="add this parameter if you want to create a subcategory, otherwise it is not required",

     *    @OA\Schema(
     *   type="integer",
     *   default=null,
     *   )

     *  ),

     *    @OA\Parameter(name="name",

     *    in="query",

     *    required=true,

     *    description="category name",

     *    @OA\Schema(type="string")

     *  ),

     *  @OA\Parameter(name="active",

     *    in="query",

     *    required=false,

     *    description="Whether a category is active or not This parameter is 1 by default",

     *    @OA\Schema(
     *             type="boolean",
     *             enum={0,1},
     *             default=1,
     *         )

     *  ),

     *  @OA\Response(response="200",

     *    description="success,return object of category",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="parent_id", type="integer"),
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
    public function store(CreateCategoryRequest $request)
    {
        try{
            $category = Category::create([
                'parent_id' => $request->parent_id ?? null,
                'name' =>  $request->name,
                'active' => $request->active ?? 1,
            ]);

            $this->setData($category);
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }

        return $this->response();
    }

    /**
     * @OA\Get(

     *  path="/categories/{category_id}",

     * tags={"Category"},

     *  summary="get a category by id",

     *  @OA\Response(response="200",

     *    description="success,return object of category",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="parent_id", type="integer"),
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
    public function show($id)
    {
        try {
            $category = Category::find($id);
            $this->setData($category);
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }

        return $this->response();
    }

    /**
     * @OA\Put(

     *  path="/categories/{category_id}",

     * tags={"Category"},

     *  summary="update a category",

     *  @OA\Parameter(name="parent_id",

     *    in="query",

     *    required=false,

     *    description="if you want to change category parent add this parameter, otherwise it is not required",

     *    @OA\Schema(type="integer")

     *  ),

     * *  @OA\Parameter(name="name",

     *    in="query",

     *    required=false,

     *    description="if you want to change category name add this parameter, otherwise it is not required",

     *    @OA\Schema(type="string")

     *  ),

     *  @OA\Parameter(name="active",

     *    in="query",

     *    required=false,

     *    description="if you want to change category active column add this parameter, otherwise it is not required",

     *    @OA\Schema(
     *             type="boolean",
     *             enum={0,1},
     *             default=1,
     *         )

     *  ),

     *  @OA\Response(response="200",

     *    description="success",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="parent_id", type="integer"),
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
    public function update(Request $request, $id)
    {
        try {
            $category = Category::find($id);
            $category->update([
                'parent_id' => $request->parent_id ?? $category->parent_id,
                'name'      => $request->name ?? $category->name,
                'active'    => $request->active ?? $category->active,
            ]);
            $this->setData($category);
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }

        return $this->response();
    }

    /**
     * @OA\Delete(

     *  path="/categories/{category_id}",

     * tags={"Category"},

     *  summary="delete a category",

     *  @OA\Response(response="200",

     *    description="success",
     *  ),

     * *  @OA\Response(response="422",

     *    description="validation error",

     * )

     *  ),

     * )

     */
    public function destroy($id)
    {
        try {
            $category = Category::find($id);
            $category->delete();
        } catch (Exception $e) {
            $this->setErrors(['message' => ['خطای سیستمی']]);
            $this->setStatus(500);
        }

        return $this->response();
    }
}
