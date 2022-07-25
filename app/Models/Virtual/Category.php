<?php

namespace App\Models\Virtual;

/**
 * @OA\Schema(
 *     title="Category",
 *     description="Category model",
 *     @OA\Xml(
 *         name="Category"
 *     )
 * )
 */

class Category
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
     *      description="Name of category",
     *      example="Rekenen"
     * )
     *
     * @var string
     */
    public $name;


    /**
     * @OA\Property(
     *      title="Color",
     *      description="Category color",
     *      example="#30a7bc"
     * )
     *
     * @var string
     */
    public $color;

    /**
     * @OA\Property(
     *      title="Image",
     *      description="Category image",
     *      example="*base64image*"
     * )
     *
     * @var string
     */
    public $image;
}
