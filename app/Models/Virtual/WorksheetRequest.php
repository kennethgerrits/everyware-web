<?php

namespace App\Models\Virtual;

/**
 * @OA\Schema(
 *     title="Worksheet body",
 *     description="Worksheet request",
 *     type="object",
 *     required={"templateId", "studentId", "questionAmount", "successAmount", "startTime", "endTime", "questions"},
 *     @OA\Xml(
 *         name="Login Body"
 *     )
 * )
 */
class WorksheetRequest
{

    /**
     * @OA\Property(
     *      title="Template ID",
     *      description="ID of a template",
     *      example="607d4567f533031b2627a1e3"
     * )
     *
     * @var string
     */
    public $templateId;

    /**
     * @OA\Property(
     *      title="Student ID",
     *      description="ID of a student",
     *      example="607d4567f533031b2627a1e3"
     * )
     *
     * @var string
     */
    public $studentId;

    /**
     * @OA\Property(
     *      title="Question amount",
     *      description="Question amount",
     *      example=10
     * )
     *
     * @var integer
     */
    public $questionAmount;

    /**
     * @OA\Property(
     *      title="Success amount",
     *      description="Success amount",
     *      example=8
     * )
     *
     * @var integer
     */
    public $successAmount;

    /**
     * @OA\Property(
     *      title="Start time",
     *      description="Start time",
     *      example="2021-04-19T09:00:47.000+00:00",
     *      format="datetime",
     * )
     *
     * @var \DateTime
     */
    public $startTime;

    /**
     * @OA\Property(
     *      title="End time",
     *      description="End time",
     *      example="2021-04-19T09:04:40.000+00:00",
     *      format="datetime",
     * )
     *
     * @var \DateTime
     */
    public $endTime;

    /**
     * @OA\Property(
     *      title="Questions",
     *      description="List of words in a wordlist",
     *      type="array",
     *      @OA\Items(
     *      type="object",
     *      @OA\Property(
     *          type="object",
     *          property="answer",
     *              @OA\Property(
     *                 type="string",
     *                 property="correctAnswer",
     *                 example="auto"
     *             ),
     *              @OA\Property(
     *                 type="string",
     *                 property="selectedAnswer",
     *                 example="auto"
     *             ),
     *              @OA\Property(
     *                 type="boolean",
     *                 property="success",
     *                 example=true
     *             ),
     *              @OA\Property(
     *                 type="string",
     *                 property="base64Image",
     *                 example="*Base64 image of answer*"
     *             ),
     *              @OA\Property(
     *                 type="object",
     *                 property="possibleAnswers",
     *                 example={"auto","boom","appel","banaan"},
     *             ),
     *       ),
     *     @OA\Property(
     *           type="integer",
     *           property="begin",
     *           example=124334234235
     *        ),
     *     @OA\Property(
     *           type="integer",
     *           property="end",
     *           example=124334234500
     *        ),
     *    )
     * )
     *
     * @var array
     */
    public $questions;
}
