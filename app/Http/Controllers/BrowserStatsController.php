<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class BrowserStatsController extends Controller
{
    public function setBrowserStats(Request $request, $visited, $page) {
        $browser = BrowserStatsController::userBrowser($_SERVER["HTTP_USER_AGENT"]);
        BrowserStatsController::browserHits($browser, $page);
        BrowserStatsController::browserIP($browser, $page);
        BrowserStatsController::browserCookie($browser, $visited, $page);
    }

    public function browserHits($browser, $page) {
        $browserPageHitExist = \Redis::command('hget', ['browser_stats_hit:'.$page, $browser]);
        $browserHitExist = \Redis::command('hget', ['browser_stats_hit', $browser]);

        if($browserHitExist) {
            \Redis::command('hset', ['browser_stats_hit', $browser, 1]);
        }
        else {
            \Redis::command('hincrby', ['browser_stats_hit', $browser, 1]);
        }


        if(empty($browserPageHitExist)) {
            \Redis::command('hset', ['browser_stats_hit:'.$page, $browser, 1]);
        }
        else {
            \Redis::command('hincrby', ['browser_stats_hit:'.$page, $browser, 1]);
        }
    }

    public function browserIP($browser, $page) {
        \Redis::command('hset', ['browser_stats_ip:'.$page, $_SERVER["REMOTE_ADDR"], $browser]);
        \Redis::command('hset', ['browser_stats_ip', $_SERVER["REMOTE_ADDR"], $browser]);
    }

    public function browserCookie($browser, $visited, $page) {
        $browserPageCookieExist = \Redis::command('hget', ['browser_stats_cookie:'.$page, $browser]);
        $browserCookieExist = \Redis::command('hget', ['browser_stats_cookie', $browser]);

        if(empty($visited)) {
            if(empty($browserCookieExist)) {
                \Redis::command('hset', ['browser_stats_cookie', $browser, 1]);
            }
            else {
                \Redis::command('hincrby', ['browser_stats_cookie', $browser, 1]);
            }

            if(empty($browserPageCookieExist)) {
                \Redis::command('hset', ['browser_stats_cookie:'.$page, $browser, 1]);
            }
            else {
                \Redis::command('hincrby', ['browser_stats_cookie:'.$page, $browser, 1]);
            }
        }
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
