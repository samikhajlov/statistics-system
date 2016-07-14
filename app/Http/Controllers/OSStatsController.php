<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class OSStatsController extends Controller
{
    public function setOSStats(Request $request, $visited, $page) {
        $os = OSStatsController::getOS($_SERVER["HTTP_USER_AGENT"]);
        $osHits = OSStatsController::osHits($os, $page);
        $osIP = OSStatsController::osIP($os, $page);
        $osCookie = OSStatsController::osCookie($os, $visited, $page);
    }

    public function osHits($os, $page) {
        $osHitExist = \Redis::command('hget', ['os_stats_hit:'.$page, $os]);
        if(empty($osHitExist)) {
            \Redis::command('hset', ['os_stats_hit:'.$page, $os, 1]);
        }
        else {
            \Redis::command('hincrby', ['os_stats_hit:'.$page, $os, 1]);
        }
        $osHits = \Redis::command('hgetall', ['os_stats_hit:'.$page]);
        return $osHits;
    }

    public function osIP($os, $page) {
        \Redis::command('hset', ['os_stats_ip:'.$page, $_SERVER["REMOTE_ADDR"], $os]);
        $osIP = \Redis::command('hgetall', ['os_stats_ip:'.$page]);
        return $osIP;
    }

    public function osCookie($os, $visited, $page) {
        $osCookieExist = \Redis::command('hget', ['os_stats_cookie:'.$page, $os]);
        if(empty($visited)) {
            if(empty($osCookieExist)) {
                \Redis::command('hset', ['os_stats_cookie:'.$page, $os, 1]);
            }
            else {
                \Redis::command('hincrby', ['os_stats_cookie:'.$page, $os, 1]);
            }
        }
        $osCookie = \Redis::command('hgetall', ['os_stats_cookie:'.$page]);
        return $osCookie;
    }

    function getOS($agent) {
        $osPlatform    =   "Unknown OS Platform";

        $osArray       =   [
            '/windows nt 10/i'     =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        ];

        foreach ($osArray as $regex => $value) {

            if (preg_match($regex, $agent)) {
                $osPlatform    =   $value;
            }
        }
        return $osPlatform;
    }
}
