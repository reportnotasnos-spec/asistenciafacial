<?php

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Welcome to Asistencia Facial MVC!',
            'description' => 'This is a simple PHP MVC framework.'
        ];

        $this->view('home/index', $data);
    }
}
