<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function setStats(Request $request) {
        $visited = $request->cookie('visited');
        $page = $_SERVER["REQUEST_URI"];

        $browser = new BrowserStatsController();
        $browserSetStats = $browser->setBrowserStats($request, $visited, $page);

        $os = new OSStatsController();
        $osSetStats = $os->setOSStats($request, $visited, $page);

        $location = new LocationStatsController();
        $locationSetStats = $location->setLocationStats($request, $visited, $page);

        $host = new HostStatsController();
        $hostSetStats = $host->setHostStats($request, $visited, $page);

//        $routeCollection = \Route::getRoutes();
//        foreach ($routeCollection as $value) {
//            echo $value->getPath(). "<br>";
//        }


        if(!$visited) {
            $response = new \Illuminate\Http\Response;
            return $response->withCookie(cookie()->forever('visited', 'yes'));
        }
    }
}
