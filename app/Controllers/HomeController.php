<?php

namespace App\Controllers;

use App\Models\Contact;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
    }

    public function index()
    {
        $title = 'Bienvenido a Mini Laravel';
        return $this->view('home', compact('title'));
    }
}
