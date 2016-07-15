<?php

namespace App\Http\Middleware;

use App\Http\Controllers\StatsController;
use Closure;
use Auth;
use App\Http\Controllers\LocationAccessController;

class SystemStats
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $visited = $request->cookie('visited');
        $stats = new StatsController();
        $systemSetStats = $stats->setStats($request);

        if(!$visited) {
            $response = $next($request);
            return $response->withCookie(cookie()->forever('visited', uniqid()));
        }
        return $next($request);
    }
}


