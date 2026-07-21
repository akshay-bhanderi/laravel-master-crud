<?php

namespace AkshayBhanderi\LaravelMasterCrud\Support;

/**
 * Resolves the class to use for a "fixed" package-provided module (currently
 * User/Role). An app overrides one by defining its own class at the same
 * conventional path — App\{Http\Controllers\portal\master,Models\portal\master}\{name}
 * — which then wins automatically, mirroring how view/component overrides work.
 */
class Modules
{
    public static function controller(string $shortName, string $subpath = 'master'): string
    {
        $prefix = $subpath !== '' ? $subpath.'\\' : '';
        $appClass = 'App\\Http\\Controllers\\portal\\'.$prefix.$shortName;

        return class_exists($appClass) ? $appClass : 'AkshayBhanderi\\LaravelMasterCrud\\Http\\Controllers\\'.$shortName;
    }

    public static function model(string $shortName, string $subpath = 'master'): string
    {
        $prefix = $subpath !== '' ? $subpath.'\\' : '';
        $appClass = 'App\\Models\\portal\\'.$prefix.$shortName;

        return class_exists($appClass) ? $appClass : 'AkshayBhanderi\\LaravelMasterCrud\\Models\\'.$shortName;
    }
}
