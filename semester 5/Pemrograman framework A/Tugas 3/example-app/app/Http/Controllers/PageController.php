<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home() {
        return "Ini halaman Home";
    }

    public function about() {
        return "Ini halaman About";
    }
}
