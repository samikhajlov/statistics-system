<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class LocationStatsController extends Controller
{
    public function setLocationStats(Request $request, $visited, $page) {
        $location = \Location::get($_SERVER["REMOTE_ADDR"]);
        $locationKey = $location->countryName . ':' . $location->regionName;
        $locationHits = LocationStatsController::locationHits($locationKey, $page);
        $locationIP = LocationStatsController::locationIP($locationKey, $page);
        $locationCookie = LocationStatsController::locationCookie($locationKey, $visited, $page);
    }

    public function locationHits($locationKey, $page) {
        $locationHitExist = \Redis::command('hget', ['location_stats_hit:'.$page, $locationKey]);
        if(empty($locationHitExist)) {
            \Redis::command('hset', ['location_stats_hit:'.$page, $locationKey, 1]);
        }
        else {
            \Redis::command('hincrby', ['location_stats_hit:'.$page, $locationKey, 1]);
        }
        $locationHits = \Redis::command('hgetall', ['location_stats_hit:'.$page]);
        return $locationHits;
    }

    public function locationIP($locationKey, $page) {
        \Redis::command('hset', ['location_stats_ip:'.$page, $_SERVER["REMOTE_ADDR"], $locationKey]);
        $locationIP = \Redis::command('hgetall', ['location_stats_ip:'.$page]);
        return $locationIP;
    }

    public function locationCookie($locationKey, $visited, $page) {
        $locationCookieExist = \Redis::command('hget', ['location_stats_cookie:'.$page, $locationKey]);
        if(empty($visited)) {
            if(empty($locationCookieExist)) {
                \Redis::command('hset', ['location_stats_cookie:'.$page, $locationKey, 1]);
            }
            else {
                \Redis::command('hincrby', ['location_stats_cookie:'.$page, $locationKey, 1]);
            }
        }
        $locationCookie = \Redis::command('hgetall', ['location_stats_cookie:'.$page]);
        return $locationCookie;
    }
}
