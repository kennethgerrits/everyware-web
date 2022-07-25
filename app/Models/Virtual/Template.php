<?php

namespace App\Models\Virtual;

/**
 * @OA\Schema(
 *     title="Template",
 *     description="Template model",
 *     @OA\Xml(
 *         name="Template"
 *     )
 * )
 */

class Template
{

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     example="607d4567f533031b2627a1e3"
     * ),
     *
     * @var string
     */
    private $id;

    /**
     * @OA\Property(
     *      title="Name",
     *      description="Name of a template",
     *      example="Werkblad 1A: Woorden"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Image",
     *      description="Image of a template",
     *      format="string",
     *      example="*Base64 image*"
     * )
     *
     * @var string
     */
    public $image;

    /**
     * @OA\Property(
     *      title="Category",
     *      description="Category of a template",
     *      example="Werkblad 1A: Woorden"
     * )
     *
     * @var string
     */
    public $category;

    /**
     * @OA\Property(
     *      title="Wordlist ID",
     *      description="Wordlist ID of a template",
     *      example="607d4567f533031b2627a1e2"
     * )
     *
     * @var string
     */
    public $wordlist_id;

    /**
     * @OA\Property(
     *      title="Welcome message",
     *      description="Welcome message of a template",
     *      example="Je kunt beginnen!"
     * )
     *
     * @var string
     */
    public $welcome_message;

    /**
     * @OA\Property(
     *      title="Question type",
     *      description="Question type of a template",
     *      example="STATIC_IMAGE"
     * )
     *
     * @var string
     */
    public $question_type;

    /**
     * @OA\Property(
     *      title="Answer type",
     *      description="Answer type of a template",
     *      example="WRITING"
     * )
     *
     * @var string
     */
    public $answer_type;

    /**
     * @OA\Property(
     *      title="Question amount",
     *      description="Question amount of a template",
     *      example=10
     * )
     *
     * @var integer
     */
    public $question_amount;

    /**
     * @OA\Property(
     *      title="Minimum amount",
     *      description="Minimum amount of a template",
     *      example=0
     * )
     *
     * @var integer
     */
    public $min_amount;

    /**
     * @OA\Property(
     *      title="Maximum amount",
     *      description="Maximum amount of a template",
     *      example=10
     * )
     *
     * @var integer
     */
    public $max_amount;

    /**
     * @OA\Property(
     *      title="Sum type",
     *      description="Sum type of a template",
     *      example="PLUS"
     * )
     *
     * @var string
     */
    public $sum_type;

    /**
     * @OA\Property(
     *      title="Reward",
     *      description="Reward of a template",
     *      example="https://www.youtube.com/watch?v=d1YBv2mWll0"
     * )
     *
     * @var string
     */
    public $reward;

    /**
     * @OA\Property(
     *      title="Difficulty",
     *      description="Difficulty of a template",
     *      type="array",
     *      example={"1","2"},
     *      @OA\Items(
     *      type="string"
     *      )
     * )
     *
     * @var integer
     */
    public $difficulties;

    /**
     * @OA\Property(
     *      title="Is available",
     *      description="Availibility of a template",
     *      example=true
     * )
     *
     * @var boolean
     */
    public $is_available;

    /**
     * @OA\Property(
     *      title="Is math",
     *      description="Template question type is math type",
     *      example=true
     * )
     *
     * @var boolean
     */
    public $is_math;

    /**
     * @OA\Property(
     *      title="Is collection",
     *      description="Indicates if the template is a collection",
     *      example=true
     * )
     *
     * @var boolean
     */
    public $is_collection;

    /**
     * @OA\Property(
     *      title="Is new",
     *      description="Indicates if the template is new",
     *      example=true
     * )
     *
     * @var boolean
     */
    public $is_new;

    /**
     * @OA\Property(
     *      title="Template ids",
     *      description="All ids of the templates",
     *      example={"1","2"},
     *      @OA\Items(
     *          type="string",
     *     )
     * )
     *
     * @var array
     */
    public $template_ids;

    /**
     * @OA\Property(
     *      title="Required templates",
     *      description="List of template ids that are required to aqquire this template",
     *      type="array",
     *      @OA\Items(
     *      type="string"
     *     )
     * )
     *
     * @var array
     */
    public $required_templates;

}
