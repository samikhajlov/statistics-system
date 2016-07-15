<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class LocationStatsController extends Controller
{
    public function setLocationStats(Request $request, $visited, $page) {
        $location = \Location::get($_SERVER["REMOTE_ADDR"]);
        $locationKey = $location->countryName . ':' . $location->regionName;
        LocationStatsController::locationHits($locationKey, $page);
        LocationStatsController::locationIP($locationKey, $page);
        LocationStatsController::locationCookie($locationKey, $visited, $page);
    }

    public function locationHits($locationKey, $page) {
        $locationPageHitExist = \Redis::command('hget', ['location_stats_hit:'.$page, $locationKey]);
        $locationHitExist = \Redis::command('hget', ['location_stats_hit', $locationKey]);

        if(empty($locationHitExist)) {
            \Redis::command('hset', ['location_stats_hit', $locationKey, 1]);
        }
        else {
            \Redis::command('hincrby', ['location_stats_hit', $locationKey, 1]);
        }

        if(empty($locationPageHitExist)) {
            \Redis::command('hset', ['location_stats_hit:'.$page, $locationKey, 1]);
        }
        else {
            \Redis::command('hincrby', ['location_stats_hit:'.$page, $locationKey, 1]);
        }
    }

    public function locationIP($locationKey, $page) {
        \Redis::command('hset', ['location_stats_ip:'.$page, $_SERVER["REMOTE_ADDR"], $locationKey]);
        \Redis::command('hset', ['location_stats_ip', $_SERVER["REMOTE_ADDR"], $locationKey]);
    }

    public function locationCookie($locationKey, $visited, $page) {
        \Redis::command('hset', ['location_stats_cookie:'.$page, $visited, $locationKey]);
        \Redis::command('hset', ['location_stats_cookie', $visited, $locationKey]);
    }
}
