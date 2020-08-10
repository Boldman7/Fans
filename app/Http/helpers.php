<?php

function trendingTags()
{
    $trending_tags = App\Hashtag::orderBy('count', 'desc')->get();

    if (count($trending_tags) > 0) {
        if (count($trending_tags) > (int) Setting::get('min_items_page', 3)) {
            $trending_tags = $trending_tags->random((int) Setting::get('min_items_page', 3));
        }
    } else {
        $trending_tags = '';
    }

    return $trending_tags;
}

function suggestedUsers()
{
    $admin_role = App\Role::where('name', 'admin')->get()->first();
    $admin_users = NULL;
    if ($admin_role != NULL) {
        $admin_users = DB::table('role_user')->where('role_id', $admin_role->id)->get();
    }

    $suggested_users = '';
    if ($admin_users != NULL) {
        $suggested_users = App\User::whereNotIn('id', Auth::user()->following()->get()->pluck('id'))->where('id', '!=', $admin_users->pluck('user_id'))->where('id', '!=', Auth::user()->id)->get();
    }
    else {
        $suggested_users = App\User::whereNotIn('id', Auth::user()->following()->get()->pluck('id'))->where('id', '!=', Auth::user()->id)->get();
    }

    if (count($suggested_users) > 0) {
        if (count($suggested_users) > (int) Setting::get('min_items_page', 3)) {
            $suggested_users = $suggested_users->random((int) Setting::get('min_items_page', 3));
        }
    } else {
        $suggested_users = '';
    }

    return $suggested_users;
}

function suggestedGroups()
{
    $suggested_groups = '';
    $suggested_groups = App\Group::whereNotIn('id', Auth::user()->groups()->pluck('group_id'))->where('type', 'open')->get();

    if (count($suggested_groups) > 0) {
        if (count($suggested_groups) > (int) Setting::get('min_items_page', 3)) {
            $suggested_groups = $suggested_groups->random((int) Setting::get('min_items_page', 3));
        }
    } else {
        $suggested_groups = '';
    }

    return $suggested_groups;
}

function suggestedPages()
{
    $suggested_pages = '';
    $suggested_pages = App\Page::whereNotIn('id', Auth::user()->pageLikes()->pluck('page_id'))->whereNotIn('id', Auth::user()->pages()->pluck('page_id'))->get();

    if (count($suggested_pages) > 0) {
        if (count($suggested_pages) > (int) Setting::get('min_items_page', 3)) {
            $suggested_pages = $suggested_pages->random((int) Setting::get('min_items_page', 3));
        }
    } else {
        $suggested_pages = '';
    }

    return $suggested_pages;
}

function verifiedBadge($timeline)
{
    $code = '<span class="verified-badge bg-success">
                    <i class="fa fa-check"></i>
                </span>';
    if($timeline->type == 'user')
    {
        if($timeline->user->verified)
        {
            $result = $code;
        }
        else
        {
            $result = false;
        }
    }
    elseif($timeline->type == 'page')
    {
        if($timeline->page->verified)
        {
            $result = $code;
        }
        else
        {
            $result = false;
        }
    }
    else
    {
        $result = false;
    }
    return $result;
}

function getOS() {

    global $user_agent;

    $os_platform  = "Unknown OS Platform";

    $os_array     = array(
        '/windows nt 10/i'      =>  'Windows 10',
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
    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser() {

    global $user_agent;

    $browser        = "Unknown Browser";

    $browser_array = array(
        '/msie/i'      => 'Internet Explorer',
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/edge/i'      => 'Edge',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Handheld Browser'
    );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}

function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
}

