<?php

namespace App\Models\Virtual;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */

class User
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
     *      title="First name",
     *      description="First name of an user",
     *      example="Gerrit"
     * )
     *
     * @var string
     */
    public $first_name;

    /**
     * @OA\Property(
     *      title="Last name",
     *      description="Last name of an user",
     *      example="Krakeling"
     * )
     *
     * @var string
     */
    public $last_name;

    /**
     * @OA\Property(
     *      title="Classgroup name",
     *      description="User classgroup",
     *      example="Klas 3/4a"
     * )
     *
     * @var string
     */
    public $class_name;
}
