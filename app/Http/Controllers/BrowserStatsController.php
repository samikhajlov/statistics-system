<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class BrowserStatsController extends Controller
{
    public function setBrowserStats(Request $request, $visited, $page) {
        $browser = BrowserStatsController::userBrowser($_SERVER["HTTP_USER_AGENT"]);
        $browserHits = BrowserStatsController::browserHits($browser, $page);
        $browserIP = BrowserStatsController::browserIP($browser, $page);
        $browserCookie = BrowserStatsController::browserCookie($browser, $visited, $page);
    }

    public function browserHits($browser, $page) {
        $browserHitExist = \Redis::command('hget', ['browser_stats_hit:'.$page, $browser]);
        if(empty($browserHitExist)) {
            \Redis::command('hset', ['browser_stats_hit:'.$page, $browser, 1]);
        }
        else {
            \Redis::command('hincrby', ['browser_stats_hit:'.$page, $browser, 1]);
        }
        $browserHits = \Redis::command('hgetall', ['browser_stats_hit:'.$page]);
        return $browserHits;
    }

    public function browserIP($browser, $page) {
        \Redis::command('hset', ['browser_stats_ip:'.$page, $_SERVER["REMOTE_ADDR"], $browser]);
        $browserIP = \Redis::command('hgetall', ['browser_stats_ip:'.$page]);
        return $browserIP;
    }

    public function browserCookie($browser, $visited, $page) {
        $browserCookieExist = \Redis::command('hget', ['browser_stats_cookie:'.$page, $browser]);
        if(empty($visited)) {
            if(empty($browserCookieExist)) {
                \Redis::command('hset', ['browser_stats_cookie:'.$page, $browser, 1]);
            }
            else {
                \Redis::command('hincrby', ['browser_stats_cookie:'.$page, $browser, 1]);
            }
        }
        $browserCookie = \Redis::command('hgetall', ['browser_stats_cookie:'.$page]);
        return $browserCookie;
    }

    function userBrowser($agent) {
        preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info);
        list(,$browser,$version) = $browser_info;
        if (preg_match("/Opera ([0-9.]+)/i", $agent, $opera)) return 'Opera '.$opera[1];
        if ($browser == 'MSIE') {
            preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie);
            if ($ie) return $ie[1].' based on IE '.$version;
            return 'IE '.$version;
        }
        if ($browser == 'Firefox') {
            preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff);
            if ($ff) return $ff[1].' '.$ff[2];
        }
        if ($browser == 'Opera' && $version == '9.80') return 'Opera '.substr($agent,-5);
        if ($browser == 'Version') return 'Safari '.$version;
        if (!$browser && strpos($agent, 'Gecko')) return 'Browser based on Gecko';
        return $browser.' '.$version;
    }
}
