<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

use function config;

class SwaggerServerUrl
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Change the server url in the Swagger UI.
        config(['swagger-ui.files.0.server_url' => $request->getSchemeAndHttpHost()]);

        return $next($request);
    }
}
