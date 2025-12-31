<?php

// Helper functions

function csrf_token() {
    return Session::generateCsrfToken();
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function redirect($path) {
    header("Location: " . URLROOT . "/" . $path);
    exit;
}

// Simple dump and die helper
function dd($value) {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

// Translation Helper
function __($key) {
    static $translations = null;
    static $currentLang = 'en';

    if ($translations === null) {
        // Determine language (default to en)
        if (isset($_SESSION['lang'])) {
            $currentLang = $_SESSION['lang'];
        }
        
        // Security check
        if (!in_array($currentLang, ['en', 'es'])) {
            $currentLang = 'en';
        }

        $path = dirname(dirname(__DIR__)) . "/resources/lang/{$currentLang}.php";
        
        if (file_exists($path)) {
            $translations = require $path;
        } else {
            $translations = [];
        }
    }

    return isset($translations[$key]) ? $translations[$key] : $key;
}

function get_current_lang() {
    return isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
}