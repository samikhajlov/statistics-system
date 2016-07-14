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

        if(!$visited) {
            $response = new \Illuminate\Http\Response;
            return $response->withCookie(cookie()->forever('visited', 'yes'));
        }
    }

    public function getBrowserStats(Request $request){
        $page = $request->page;
        $browserHits = \Redis::command('hgetall', ['browser_stats_hit:'.$page]);
        $browserIP = \Redis::command('hgetall', ['browser_stats_ip:'.$page]);
        $browserCookie = \Redis::command('hgetall', ['browser_stats_cookie:'.$page]);

        $allbrowsers = [];

        foreach($browserHits as $browser => $hits){
            $allbrowsers[$browser]["hits"] = $hits;
        }
        foreach($browserIP as $ip => $browser){
            $allbrowsers[$browser]["ip"][$ip] = "catch";
        }
        foreach($browserCookie as $browser => $cookie){
            $allbrowsers[$browser]["cookie"] = $cookie;
        }
        dd($allbrowsers);

        return $allbrowsers;
    }

    public function getOSStats(Request $request){
        $page = $request->page;

        $osHits = \Redis::command('hgetall', ['os_stats_hit:'.$page]);
        $osIP = \Redis::command('hgetall', ['os_stats_ip:'.$page]);
        $osCookie = \Redis::command('hgetall', ['os_stats_cookie:'.$page]);

        $allOS = [];

        foreach($osHits as $os => $hits){
            $allOS[$os]["hits"] = $hits;
        }
        foreach($osIP as $ip => $os){
            $allOS[$os]["ip"][$ip] = "catch";
        }
        foreach($osCookie as $os => $cookie){
            $allOS[$os]["cookie"] = $cookie;
        }
        dd($allOS);

        return $allOS;
    }

    public function getLocationStats(Request $request){
        $page = $request->page;

        $locationHits = \Redis::command('hgetall', ['location_stats_hit:'.$page]);
        $locationIP = \Redis::command('hgetall', ['location_stats_ip:'.$page]);
        $locationCookie = \Redis::command('hgetall', ['location_stats_cookie:'.$page]);

        $alllocation = [];

        foreach($locationHits as $location => $hits){
            $alllocation[$location]["hits"] = $hits;
        }
        foreach($locationIP as $ip => $location){
            $alllocation[$location]["ip"][$ip] = "catch";
        }
        foreach($locationCookie as $location => $cookie){
            $alllocation[$location]["cookie"] = $cookie;
        }

        return $alllocation;
    }

    public function getHostStats(Request $request){
        $page = $request->page;

        $hostHits = \Redis::command('hgetall', ['host_stats_hit:'.$page]);
        $hostIP = \Redis::command('hgetall', ['host_stats_ip:'.$page]);
        $hostCookie = \Redis::command('hgetall', ['host_stats_cookie:'.$page]);
        $allhost = [];

        foreach($hostHits as $host => $hits){
            $allhost[$host]["hits"] = $hits;
        }
        foreach($hostIP as $ip => $host){
            $allhost[$host]["ip"][$ip] = "catch";
        }
        foreach($hostCookie as $host => $cookie){
            $allhost[$host]["cookie"] = $cookie;
        }
        return $allhost;
    }
}
