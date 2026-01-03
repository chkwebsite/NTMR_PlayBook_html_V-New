<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

function pr($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function prd($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}



function getMenu($location, $active = '')
{
    $session_data = Session::get('LanguageSession', env('DEFAULT_LANGUAGE'));

    // MAIN LANGUAGE MENU
    $englishmenuItems = DB::table('menus as a')
        ->join('menu_items as b', 'a.id', '=', 'b.menu_id')
        ->where('location', $location)
        ->where('b.is_deleted', 0)
        ->select(
            'b.id as id',
            'b.menu_type',
            'b.title',
            'b.url',
            'b.target',
            'b.parent_id'
        )
        ->get()
        ->keyBy('id');

    // TRANSLATED MENU
    if ($session_data != 1) {
        $translatedmenuItems = DB::table('menus as a')
            ->join('menu_items_ml as b', 'a.id', '=', 'b.menu_id')
            ->where('location', $location)
            ->where('b.lang_id', $session_data)
            ->where('b.is_deleted', 0)
            ->select(
                'b.menu_item_id as id',
                'b.menu_type',
                'b.title',
                'b.url',
                'b.target',
                'b.parent_id'
            )
            ->get()
            ->keyBy('id');
    } else {
        $translatedmenuItems = collect();
    }


    $menuItems = $englishmenuItems->map(function ($item) use ($translatedmenuItems) {
        return $translatedmenuItems[$item->id] ?? $item;
    });


    $itemsGrouped = $menuItems->groupBy(function ($item) {
        return $item->parent_id ?: 0;
    });


    $buildMenu = function ($parentId) use (&$buildMenu, $itemsGrouped) {

        if (!isset($itemsGrouped[$parentId])) {
            return '';
        }

        $html = '<ul class="dropdown-menu">';

        foreach ($itemsGrouped[$parentId] as $item) {
            $target ="_self";

            if($item->menu_type =='custom' ){
                if (strpos($item->url, 'http') === 0) {
                    $target="_blank";
                    $url = $item->url;
                }
                else{
                    $url = ($item->url == '/') ? '/' :  '/' . $item->url;
                }

            }elseif($item->menu_type =='reform'){
               $url = ($item->url == '/') ? '/' : '/' . 'reform-elements' . '/' . $item->url;
            }
            else{
              $url = ($item->url == '/') ? '/' : '/' . $item->menu_type . '/' . $item->url;
            }

            $hasChildren = isset($itemsGrouped[$item->id]);


            if ($hasChildren) {
                $html .= '
                    <li class="dropdown dropend">
                        <a class="dropdown-item dropdown-toggle" href="' . $url . '" data-bs-toggle="dropdown">
                            ' . $item->title . '
                        </a>
                        ' . $buildMenu($item->id) . '
                    </li>';
            } else {
                $html .= '
                    <li>
                        <a class="dropdown-item" href="' . $url . '">' . $item->title . '</a>
                    </li>';
            }
        }

        $html .= '</ul>';
        return $html;
    };

    // MAIN MENU
    if($location == 'header'){
      $menu = '<ul class="navbar-nav nav-link-main me-auto">';
    }else{
        $menu = '<ul class="navbar-nav  me-auto">';
    }


    if (isset($itemsGrouped[0])) {
        foreach ($itemsGrouped[0] as $item) {
            $target ="_self";
            if($item->menu_type =='custom' ){
              if (strpos($item->url, 'http') === 0) {
                    $target="_blank";
                    $url = $item->url;
                }
                else{
                    $url = ($item->url == '/') ? '/' :  '/' . $item->url;
                }

            }elseif($item->menu_type =='reform'){
               $url = ($item->url == '/') ? '/' : '/' . 'reform-elements' . '/' . $item->url;
            }
            else{
              $url = ($item->url == '/') ? '/' : '/' . $item->menu_type . '/' . $item->url;
            }

            $hasChildren = isset($itemsGrouped[$item->id]);
            $arrow = $hasChildren ? '<span class="arrow"></span>' : '';

            $last = last(request()->segments());
            if (!$last) $last = '/';
            if ($hasChildren) {
                $menu .= '
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle ' . (($last == $item->url) ? 'active' : '') . '" href="' . $url . '" data-bs-toggle="dropdown">
                            ' . $item->title . ' ' . $arrow . '
                        </a>
                        ' . $buildMenu($item->id) . '
                    </li>';
            } else {

                if($location == 'footer'){
                     $menu .= '
                    <li class="nav-item">
                        <a class="nav-link no-underline' . (($last == $item->url) ? 'active' : '') . '" href="' . $url . '">' . $item->title . '</a>
                    </li>';
                }else{
                     $menu .= '
                    <li class="nav-item">
                        <a class="nav-link ' . (($last == $item->url) ? 'active' : '') . '" target="'. $target .'" href="' . $url . '">' . $item->title . '</a>
                    </li>';
                }

            }
        }
    }

    $menu .= '</ul>';
    return $menu;
}




function getSetting($key){
    $setting = DB::table('settings')
                    ->where('key', $key)
                    ->value('value');

    return $setting;

}

function getWidget($key){
    $session_data = Session::get('LanguageSession',env('DEFAULT_LANGUAGE'));


    $widget = DB::table('widget')
        ->where('key', $key)
        ->where('lang_id', $session_data)
        ->where('is_deleted', 0)
        ->value('content');

    if (!$widget) {
        $widget = DB::table('widget')
            ->where('key', $key)
            ->where('lang_id', 1)
            ->where('is_deleted', 0)
            ->value('content');
    }



    return $widget;

}

function getBanner($key){
    $session_data = Session::get('LanguageSession',env('DEFAULT_LANGUAGE'));


        $banner = DB::table('banner')
                    ->where('key', $key)
                    ->value('image');




    return $banner;

}

