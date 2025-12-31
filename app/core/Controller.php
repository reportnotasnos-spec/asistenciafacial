<?php

class Controller
{
    public function model($model)
    {
        return new $model();
    }

    public function view($view, $data = [])
    {
        // Construct the path to the specific view file
        $view_path = APP_ROOT . '/resources/views/' . $view . '.php';

        if (file_exists($view_path)) {
            // The default layout will require $view_path
            require_once APP_ROOT . '/resources/views/layouts/default.php';
        } else {
            die('View does not exist');
        }
    }
}
