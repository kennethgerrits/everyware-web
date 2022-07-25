<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wordlist;
use App\Transformers\TemplateTransformer;
use App\Transformers\WordlistTransformer;
use Illuminate\Http\Request;

class WordlistApiController extends APIController
{

    public function __construct()
    {
        parent::__construct();
        $this->defaultTransformer = WordlistTransformer::class;
    }

    /**
     * @OA\Get(
     *      path="/wordlists",
     *      operationId="getWordlists",
     *      tags={"Wordlists"},
     *      summary="Get all wordlists",
     *      description="Returns all wordlists",
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Wordlist")
     *       )
     *     )
     */
    public function getWordlists()
    {
        return $this->multiple(Wordlist::all());
    }

    /**
     * @OA\Get(
     *      path="/wordlists/{ids}",
     *      operationId="getWordlistsByIds",
     *      tags={"Wordlists"},
     *      summary="Get wordlists by ids",
     *      description="Returns wordlists by ids",
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Wordlist")
     *       )
     *     )
     */
    public function getWordlistsByIds($ids)
    {

        return $this->multiple(Wordlist::findMany($ids));
    }

    /**
     * @OA\Get(
     *      path="/wordlist/{id}",
     *      operationId="getWordlistById",
     *      tags={"Wordlists"},
     *      summary="Get a wordlist by ID",
     *      description="Returns a wordlist by ID",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of wordlist",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/Wordlist")
     *       )
     *     )
     */
    public function getWordlistById($id)
    {
        return $this->single(Wordlist::where('_id', $id)->first());
    }
}
