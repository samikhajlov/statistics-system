<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HostStatsController extends Controller
{
    public function setHostStats(Request $request, $visited, $page) {
        if(!empty($_SERVER["HTTP_REFERER"])) {
            $referer = parse_url($_SERVER["HTTP_REFERER"]);
            $host = $referer["host"];
            $hostHits = HostStatsController::hostHits($host, $page);
            $hostIP = HostStatsController::hostIP($host, $page);
            $hostCookie = HostStatsController::hostCookie($host, $visited, $page);
        }
        /*
        may be empty if
        entered the site URL in browser address bar itself.
        visited the site by a browser-maintained bookmark.
        visited the site as first page in the window/tab.
        switched from a https URL to a http URL.
        switched from a https URL to a different https URL.
        has security software installed (antivirus/firewall/etc) which strips the referrer from all requests.
        is behind a proxy which strips the referrer from all requests.
        visited the site programmatically (like, curl) without setting the referrer header (searchbots!).
        */
    }

    public function hostHits($host, $page) {
        $hostHitExist = \Redis::command('hget', ['host_stats_hit:'.$page, $host]);
        if(empty($hostHitExist)) {
            \Redis::command('hset', ['host_stats_hit:'.$page, $host, 1]);
        }
        else {
            \Redis::command('hincrby', ['host_stats_hit:'.$page, $host, 1]);
        }
        $hostHits = \Redis::command('hgetall', ['host_stats_hit:'.$page]);
        return $hostHits;
    }

    public function hostIP($host, $page) {
        \Redis::command('hset', ['host_stats_ip:'.$page, $_SERVER["REMOTE_ADDR"], $host]);
        $hostIP = \Redis::command('hgetall', ['host_stats_ip:'.$page]);
        return $hostIP;
    }

    public function hostCookie($host, $visited, $page) {
        $hostCookieExist = \Redis::command('hget', ['host_stats_cookie:'.$page, $host]);
        if(empty($visited)) {
            if(empty($hostCookieExist)) {
                \Redis::command('hset', ['host_stats_cookie:'.$page, $host, 1]);
            }
            else {
                \Redis::command('hincrby', ['host_stats_cookie:'.$page, $host, 1]);
            }
        }
        $hostCookie = \Redis::command('hgetall', ['host_stats_cookie:'.$page]);
        return $hostCookie;
    }
}
