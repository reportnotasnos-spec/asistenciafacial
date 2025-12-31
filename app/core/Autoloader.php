<?php

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            // Define an array of directories to search in (relative to APP_ROOT)
            $dirs = [
                'app/core/',
                'app/controllers/',
                'app/models/',
                'app/helpers/',
                'app/services/'
            ];

            foreach ($dirs as $dir) {
                $file = APP_ROOT . '/' . $dir . $class . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        });
    }
}