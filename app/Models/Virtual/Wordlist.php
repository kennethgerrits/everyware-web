<?php

namespace App\Models\Virtual;

/**
 * @OA\Schema(
 *     title="Wordlist",
 *     description="Wordlist Model",
 *     @OA\Xml(
 *         name="Wordlist"
 *     )
 * )
 */
class Wordlist
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
     *      title="Words",
     *      description="List of words in a wordlist",
     *      type="array",
     *      @OA\Items(
     *      type="object",
     *      @OA\Property(
     *          type="string",
     *          property="name",
     *          example="Aap"
     *       ),
     *     @OA\Property(
     *          type="string",
     *          property="url",
     *          example="*Base64 app image*"
     *       ),
     *     )
     * )
     *
     * @var array
     */
    public $words;
}
