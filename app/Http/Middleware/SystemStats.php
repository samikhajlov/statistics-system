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
        $stats = new StatsController();
        $systemSetStats = $stats->setStats($request);
        return $next($request);
    }
}


