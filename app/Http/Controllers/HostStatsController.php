<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HostStatsController extends Controller
{
    public function setHostStats(Request $request, $visited) {
        if(!empty($_SERVER["HTTP_REFERER"])) {
            $referer = parse_url($_SERVER["HTTP_REFERER"]);
            $host = $referer["host"];

            $hostHits = HostStatsController::hostHits($host);
            $hostIP = HostStatsController::hostIP($host);
            $hostCookie = HostStatsController::hostCookie($host, $visited);
        }
    }

    public function hostHits($host) {
        $hostHitExist = \Redis::command('hget', ['host_stats_hit', $host]);
        if(empty($hostHitExist)) {
            \Redis::command('hset', ['host_stats_hit', $host, 1]);
        }
        else {
            \Redis::command('hincrby', ['host_stats_hit', $host, 1]);
        }
        $hostHits = \Redis::command('hgetall', ['host_stats_hit']);
        return $hostHits;
    }

    public function hostIP($host) {
        \Redis::command('hset', ['host_stats_ip', $_SERVER["REMOTE_ADDR"], $host]);
        $hostIP = \Redis::command('hgetall', ['host_stats_ip']);
        return $hostIP;
    }

    public function hostCookie($host, $visited) {
        $hostCookieExist = \Redis::command('hget', ['host_stats_cookie', $host]);
        if(empty($visited)) {
            if(empty($hostCookieExist)) {
                \Redis::command('hset', ['host_stats_cookie', $host, 1]);
            }
            else {
                \Redis::command('hincrby', ['host_stats_cookie', $host, 1]);
            }
        }
        $hostCookie = \Redis::command('hgetall', ['host_stats_cookie']);
        return $hostCookie;
    }
}
