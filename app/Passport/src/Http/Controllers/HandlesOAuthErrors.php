<?php

namespace App\Passport\src\Http\Controllers;

use App\Passport\src\Exceptions\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException as LeagueException;
use Nyholm\Psr7\Response as Psr7Response;

trait HandlesOAuthErrors
{
    use ConvertsPsrResponses;

    /**
     * Perform the given callback with exception handling.
     *
     * @param  \Closure  $callback
     * @return mixed
     *
     * @throws OAuthServerException
     */
    protected function withErrorHandling($callback)
    {
        try {
            return $callback();
        } catch (LeagueException $e) {
            throw new OAuthServerException(
                $e,
                $this->convertResponse($e->generateHttpResponse(new Psr7Response))
            );
        }
    }
}
