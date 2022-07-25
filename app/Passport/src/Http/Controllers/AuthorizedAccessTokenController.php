<?php

namespace App\Passport\src\Http\Controllers;

use App\Passport\src\RefreshTokenRepository;
use App\Passport\src\TokenRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorizedAccessTokenController
{
    /**
     * The token repository implementation.
     *
     * @var TokenRepository
     */
    protected $tokenRepository;

    /**
     * The refresh token repository implementation.
     *
     * @var RefreshTokenRepository
     */
    protected $refreshTokenRepository;

    /**
     * Create a new controller instance.
     *
     * @param  TokenRepository  $tokenRepository
     * @param  RefreshTokenRepository  $refreshTokenRepository
     * @return void
     */
    public function __construct(TokenRepository $tokenRepository, RefreshTokenRepository $refreshTokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    /**
     * Get all of the authorized tokens for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser(Request $request)
    {
        $tokens = $this->tokenRepository->forUser($request->user()->getAuthIdentifier());

        return $tokens->load('client')->filter(function ($token) {
            return ! $token->client->firstParty() && ! $token->revoked;
        })->values();
    }

    /**
     * Delete the given token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tokenId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $tokenId)
    {
        $token = $this->tokenRepository->findForUser(
            $tokenId, $request->user()->getAuthIdentifier()
        );

        if (is_null($token)) {
            return new Response('', 404);
        }

        $token->revoke();

        $this->refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
