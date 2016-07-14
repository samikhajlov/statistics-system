<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class LocationStatsController extends Controller
{
    public function setLocationStats(Request $request, $visited) {
        $location = \Location::get($_SERVER["REMOTE_ADDR"]);
        $locationKey = $location->countryName . ':' . $location->regionName;
        $locationHits = LocationStatsController::locationHits($locationKey);
        $locationIP = LocationStatsController::locationIP($locationKey);
        $locationCookie = LocationStatsController::locationCookie($locationKey, $visited);
    }

    public function locationHits($locationKey) {
        $locationHitExist = \Redis::command('hget', ['location_stats_hit', $locationKey]);
        if(empty($locationHitExist)) {
            \Redis::command('hset', ['location_stats_hit', $locationKey, 1]);
        }
        else {
            \Redis::command('hincrby', ['location_stats_hit', $locationKey, 1]);
        }
        $locationHits = \Redis::command('hgetall', ['location_stats_hit']);
        return $locationHits;
    }

    public function locationIP($locationKey) {
        \Redis::command('hset', ['location_stats_ip', $_SERVER["REMOTE_ADDR"], $locationKey]);
        $locationIP = \Redis::command('hgetall', ['location_stats_ip']);
        return $locationIP;
    }

    public function locationCookie($locationKey, $visited) {
        $locationCookieExist = \Redis::command('hget', ['location_stats_cookie', $locationKey]);
        if(empty($visited)) {
            if(empty($locationCookieExist)) {
                \Redis::command('hset', ['location_stats_cookie', $locationKey, 1]);
            }
            else {
                \Redis::command('hincrby', ['location_stats_cookie', $locationKey, 1]);
            }
        }
        $locationCookie = \Redis::command('hgetall', ['location_stats_cookie']);
        return $locationCookie;
    }
}
