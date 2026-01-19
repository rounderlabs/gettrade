<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller

{
    public function Map() {
        $pages = [

            route('home'),
            route('login'),
            route('register'),
            route('dashboard'),


        ];
        return response()->view('sitemap', [
            'pages' => $pages
        ])->header('Content-Type', 'text/xml');
    }
}


