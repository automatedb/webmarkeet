<?php
/**
 * Created by PhpStorm.
 * User: nickleus
 * Date: 05/08/17
 * Time: 20:55
 */

namespace App\Helpers;


use Illuminate\Support\Facades\Request;

class Menu
{
    public static function activeMenu($uri='')
    {
        $active = '';

        if (Request::is(Request::segment(1) . '/' . $uri . '/*') || Request::is(Request::segment(1) . '/' . $uri) || Request::is($uri))
        {
            $active = 'active';
        }

        return $active;
    }
}