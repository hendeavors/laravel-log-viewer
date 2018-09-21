<?php

namespace Rap2hpoutre\LaravelLogViewer;

use Illuminate\Support\Facades\Config;

/**
 * Class Factory
 * @package Rap2hpoutre\LaravelLogViewer
 */
class Factory
{
    public static function create()
    {
        $viewer = new LaravelLogViewer();

        $isServerPaged = function_exists('config') ? config('logviewer.server_paging', false) : Config::get('laravel-log-viewer::config.server_paging', false);

        if($isServerPaged) {
            $viewer = new LaravelPagedLogViewer($viewer, app('request')->all());
        }

        return $viewer;
    }
}