<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        return view('dashboard');
    }
}
