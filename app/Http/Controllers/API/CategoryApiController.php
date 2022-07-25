<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Template;
use App\Models\User;
use App\Transformers\CategoryTransformer;

class CategoryApiController extends APIController
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultTransformer = CategoryTransformer::class;
    }

    /**
     * @OA\Get(
     *      path="/categories",
     *      operationId="getAllCategories",
     *      tags={"Categories"},
     *      summary="Get all categories",
     *      description="Returns all categories",
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Category")
     *       )
     *     )
     */
    public function getAllCategories()
    {
        return $this->multiple(Category::all());
    }

    /**
     * @OA\Get(
     *      path="/categories/student/{id}",
     *      operationId="getStudentCategories",
     *      tags={"Categories"},
     *      summary="Get all categories for a student",
     *      description="Returns all categories for a student",
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Category")
     *       )
     *     )
     */
    public function getStudentCategories($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error('Het opgegeven ID bestaat niet.', 404);
        }

        $templates = [];

        if($user->studentGroups){
            foreach ($user->studentGroups as $studentGroup){
                if($studentGroup->templates){
                    $templates += $studentGroup->templates;
                }
            }
            $templates = array_unique($templates);
        }

        $ids = Template::whereIn('_id', $templates)->groupBy('category_id')->pluck('category_id');

        $categories = Category::findMany($ids);

        return $this->multiple($categories);
    }
}
