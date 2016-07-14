<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class OSStatsController extends Controller
{
    public function setOSStats(Request $request, $visited) {
        $os = OSStatsController::getOS($_SERVER["HTTP_USER_AGENT"]);
        $osHits = OSStatsController::osHits($os);
        $osIP = OSStatsController::osIP($os);
        $osCookie = OSStatsController::osCookie($os, $visited);
    }

    public function osHits($os) {
        $osHitExist = \Redis::command('hget', ['os_stats_hit', $os]);
        if(empty($osHitExist)) {
            \Redis::command('hset', ['os_stats_hit', $os, 1]);
        }
        else {
            \Redis::command('hincrby', ['os_stats_hit', $os, 1]);
        }
        $osHits = \Redis::command('hgetall', ['os_stats_hit']);
        return $osHits;
    }

    public function osIP($os) {
        \Redis::command('hset', ['os_stats_ip', $_SERVER["REMOTE_ADDR"], $os]);
        $osIP = \Redis::command('hgetall', ['os_stats_ip']);
        return $osIP;
    }

    public function osCookie($os, $visited) {
        $osCookieExist = \Redis::command('hget', ['os_stats_cookie', $os]);
        if(empty($visited)) {
            if(empty($osCookieExist)) {
                \Redis::command('hset', ['os_stats_cookie', $os, 1]);
            }
            else {
                \Redis::command('hincrby', ['os_stats_cookie', $os, 1]);
            }
        }
        $osCookie = \Redis::command('hgetall', ['os_stats_cookie']);
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
