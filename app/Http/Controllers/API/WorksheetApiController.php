<?php

namespace App\Http\Controllers\API;

use App\Enums\AnswerType;
use App\Enums\QuestionType;
use App\Models\Template;
use App\Models\Worksheet;
use App\Transformers\TemplateTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorksheetApiController extends APIController
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultTransformer = TemplateTransformer::class;
    }

    /**
     * @OA\Post(
     *      path="/worksheets",
     *      operationId="postWorksheet",
     *      tags={"Worksheets"},
     *      summary="Post a single worksheet",
     *      description="Post a single worksheet",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/WorksheetRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="The worksheet is saved successfully!",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       )
     *     )
     */
    public function postWorksheet(Request $request)
    {
        $worksheet = new Worksheet();
        $template = Template::find($request->input('templateId'));
        $worksheet->fill([
            'template_id' => $template->id,
            'question_type' => $template->question_type,
            'answer_type' => $template->answer_type,
            'min_amount' => $template->min_amount,
            'max_amount' => $template->max_amount,
            'sum_type' => $template->sum_type,
            'cesuur' => $template->cesuur,
            'user_id' => $request->input('studentId'),
            'question_amount' => $request->input('questionAmount'),
            'success_amount' => $request->input('successAmount'),
            'started_at' => Carbon::parse($request->input('startTime'))->toDateTime(),
            'ended_at' => Carbon::parse($request->input('endTime'))->toDateTime(),
        ]);

        $results = [];

        foreach ($request->input('questions') as $question) {
            $duration = $question['end'] - $question['begin'];
            $item = [
                'correct_answer' => $question['answer']['correctAnswer'],
                'selected_answer' => $question['answer']['selectedAnswer'],
                'success' => $question['answer']['success'],
                'duration' => $duration,
            ];

            $mainTemplate = $template;

            if ($template->is_collection) {
                $item['template_id'] = $question['templateId'];
                $mainTemplate = Template::find($question['templateId']);
                $item['question_type'] = $mainTemplate->question_type;
                $item['answer_type'] = $mainTemplate->answer_type;
            }

            if ($mainTemplate->question_type === QuestionType::STATIC_IMAGE) {
                $item['image_question'] = $question['imageUrl'];
            } else if ($mainTemplate->question_type === QuestionType::LISTENING) {
                $item['audio_question'] = $question['base64Audio'];
            }

            if ($mainTemplate->answer_type === AnswerType::MULTIPLE_CHOICE) {
                $item['possible_answers'] = $question['answer']['possibleAnswers'];
            } elseif ($mainTemplate->answer_type === AnswerType::WRITING) {
                $item['image'] = $question['answer']['base64Image'];
            } elseif ($mainTemplate->answer_type === AnswerType::VOICE) {
                $item['audio_input'] = $question['answer']['base64Audio'];
            } else if ($mainTemplate->question_type === QuestionType::DRAG_IMAGE) {
                $item['image'] = $question['answer']['base64Image'];
            }

            $results[] = $item;
        }

        $worksheet->questions = $results;

        $worksheet->save();

        return $this->success('The worksheet is saved successfully!', 200);
    }
}
