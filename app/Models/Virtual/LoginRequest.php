<?php

namespace App\Models\Virtual;

/**
 * @OA\Schema(
 *     title="Login Body",
 *     description="Login request",
 *     type="object",
 *     required={"email", "password"},
 *     @OA\Xml(
 *         name="Login Body"
 *     )
 * )
 */

class LoginRequest
{

    /**
     * @OA\Property(
     *      title="Email",
     *      description="Email of an user",
     *      example="gerrit@krakeling.net"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="Password",
     *      description="Password of an user",
     *      example="geheim"
     * )
     *
     * @var string
     */
    public $password;
}
