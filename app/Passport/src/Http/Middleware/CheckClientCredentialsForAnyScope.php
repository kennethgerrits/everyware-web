<?php

namespace App\Passport\src\Http\Middleware;

use App\Passport\src\Exceptions\MissingScopeException;
use App\Passport\src\Token;
use Illuminate\Auth\AuthenticationException;

class CheckClientCredentialsForAnyScope extends CheckCredentials
{
    /**
     * Validate token credentials.
     *
     * @param  Token  $token
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function validateCredentials($token)
    {
        if (! $token) {
            throw new AuthenticationException;
        }
    }

    /**
     * Validate token credentials.
     *
     * @param  Token  $token
     * @param  array  $scopes
     * @return void
     *
     * @throws MissingScopeException
     */
    protected function validateScopes($token, $scopes)
    {
        if (in_array('*', $token->scopes)) {
            return;
        }

        foreach ($scopes as $scope) {
            if ($token->can($scope)) {
                return;
            }
        }

        throw new MissingScopeException($scopes);
    }
}
