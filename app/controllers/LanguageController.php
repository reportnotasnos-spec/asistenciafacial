<?php

class LanguageController extends Controller
{
    public function set($lang)
    {
        // Whitelist allowed languages
        $allowed = ['en', 'es'];

        if (in_array($lang, $allowed)) {
            $_SESSION['lang'] = $lang;
        }

        // Redirect back to previous page or home
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URL_ROOT;
        
        header('Location: ' . $redirect);
        exit;
    }
}
