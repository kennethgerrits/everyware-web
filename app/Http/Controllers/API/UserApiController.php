<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserApiController extends APIController
{
    public function __construct()
    {
        parent::__construct();
        $this->defaultTransformer = UserTransformer::class;
    }


    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"Users"},
     *      summary="Login a single user",
     *      description="Returns a single user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="De combinatie van email en wachtwoord is niet bij ons bekend.",
     *       )
     *     )
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if(!$user->isStudent()){
                return $this->error('De gebruiker dient een student te zijn.', 404);
            }

            $user->token = $user->createToken('API accesstoken')->accessToken;
            return $this->single($user);
        }

        return $this->error('De combinatie van email en wachtwoord is niet bij ons bekend.', 404);
    }
}
