<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthDevMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Helper::isDebugAuthUser()) {
            // @codeCoverageIgnoreStart
            return redirect()->to('/login'); // 'This action is unauthorized';
            // @codeCoverageIgnoreEnd
        }

        return $next($request);
    }
}
