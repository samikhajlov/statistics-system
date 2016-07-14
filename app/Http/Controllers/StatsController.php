<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function setStats(Request $request) {
        $visited = $request->cookie('visited');
        $browser = new BrowserStatsController();
        $browserSetStats = $browser->setBrowserStats($request, $visited);
        //dd($visited);
        if(!$visited) {
            $response = new \Illuminate\Http\Response;
            return $response->withCookie(cookie()->forever('visited', 'yes'));
        }
    }
}
