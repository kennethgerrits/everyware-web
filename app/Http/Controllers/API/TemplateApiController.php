<?php

namespace App\Http\Controllers\API;

use App\Enums\QuestionType;
use App\Models\Template;
use App\Models\User;
use App\Models\Worksheet;
use App\Transformers\TemplateTransformer;

class TemplateApiController extends APIController
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultTransformer = TemplateTransformer::class;
    }

    /**
     * @OA\Get(
     *      path="/templates",
     *      operationId="getAllTemplates",
     *      tags={"Templates"},
     *      summary="Get list of templates",
     *      description="Returns list of templates",
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Template")
     *       )
     *     )
     */
    public function getAllTemplates()
    {
        return $this->multiple(Template::normal()->get());
    }

    /**
     * @OA\Get(
     *      path="/templates/{name}",
     *      operationId="getTemplatesByName",
     *      tags={"Templates"},
     *      @OA\Parameter(
     *          name="name",
     *          description="Name of template",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      summary="Get list of templates by name",
     *      description="Returns list of templates by name",
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Template")
     *       )
     *     )
     */
    public function getTemplatesByName($name)
    {
        return $this->multiple(Template::normal()->where('name', $name)->get());
    }

    /**
     * @OA\Get(
     *      path="/templates/student/{id}",
     *      operationId="getTemplatesByStudentId",
     *      tags={"Templates"},
     *      @OA\Parameter(
     *          name="id",
     *          description="user ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      summary="Get list of templates by user ID",
     *      description="Returns list of templates by user ID",
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Template")
     *       ),
     *     @OA\Response(
     *          response=404,
     *          description="Het opgegeven ID bestaat niet.",
     *       )
     *     )
     */
    public function getTemplatesByStudentId($id)
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

        //delete normal()-> to get a template collection (is_collection true means its a collection) has template_ids to use on multiple api call
        $templates = Template::whereIn('_id', $templates)->where('is_available', true)
            ->where(function($q){
                $q->where('is_collection', true);
                $q->orWhere(function($q2){
                    $q2->whereNotNull('wordlist_id');
                });
                $q->orWhere('question_type', QuestionType::ARITHMETIC_SUM_TEXT);
            })
            ->get();

        // Remove templates.
        $templates = $this->removeTemplatesBasedOnCesuur($templates, $user);

        foreach ($templates as $template){
            if(!Worksheet::where('template_id', $template->id)->where('user_id', $id)->first()){
                $template->is_new = true;
            }else{
                $template->is_new = false;
            }
        }


        return $this->multiple($templates);
    }

    private function removeTemplatesBasedOnCesuur($templates, $user)
    {
        $filteredTemplates = collect();

        // Loop over all templates.
        foreach ($templates as $template) {

            // If templates has required templates
            if ($template->required_templates) {

                $worksheets = Worksheet::whereIn('template_id', $template->required_templates)->where('user_id', $user->_id)->get();

                if ($worksheets->count()) {
                    // Loop over worksheets to check if succesfull (opposed to cesuur).
                    foreach ($worksheets as $worksheet) {
                        $correctAnswers = 0;

                        foreach ($worksheet->questions as $question) {
                            if ($question['success']) {
                                $correctAnswers++;
                            }
                        }

                        if ($worksheet->cesuur) {
                            if ($correctAnswers >= $worksheet->cesuur) {
                                $filteredTemplates->add($template);
                                break;
                            }
                        }
                    }
                }
            }else{
                $filteredTemplates->add($template);
            }
        }
        return $filteredTemplates;
    }

    /**
     * @OA\Get(
     *      path="/templates/multiple/{ids}",
     *      operationId="getTemplatesByIds",
     *      tags={"Templates"},
     *      @OA\Parameter(
     *          name="ids",
     *          description="imploded ids",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      summary="Get list of templates by ids",
     *      description="Returns list of templates by ids",
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Template")
     *       ),
     *     @OA\Response(
     *          response=404,
     *          description="De opgegeven IDS bestaan niet.",
     *       )
     *     )
     */
    public function getTemplatesByIds($ids)
    {
        $templates = Template::findMany(explode(',', $ids));

        if(!$templates){
            return $this->error('De opgegeven IDS bestaan niet.', 404);
        }

        foreach ($templates as $template){
            $template->all = true;
            $template->is_new = false;
        }

        return $this->multiple($templates);
    }

    /**
     * @OA\Get(
     *      path="/templates/{category}",
     *      operationId="getTemplatesByCategory",
     *      tags={"Templates"},
     *      summary="Get list of templates by category",
     *      description="Returns list of templates by category",
     *     @OA\Parameter(
     *          name="category",
     *          description="Category of template",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Template")
     *       )
     *     )
     */
    public function getTemplatesByCategory($category)
    {
        return $this->multiple(Template::normal()->where('category_id', $category)->get());
    }
}
