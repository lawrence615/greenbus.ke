<?php

namespace App\Http\Controllers\Admin\Tour;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Tour;

class StandardController extends Controller
{
    public function __construct()
    {
        throw new \Exception('Not implemented');
    }

    public function index()
    {
        throw new \Exception('Not implemented');
    }

    public function create()
    {
        throw new \Exception('Not implemented');
    }

    public function store(Request $request)
    {
        throw new \Exception('Not implemented');
    }

    public function edit(Tour $tour)
    {
        throw new \Exception('Not implemented');
    }

    public function update(Request $request, Tour $tour)
    {
        throw new \Exception('Not implemented');
    }

    public function destroy(Tour $tour)
    {
        throw new \Exception('Not implemented');
    }

    public function toggleStatus(Tour $tour)
    {
        throw new \Exception('Not implemented');
    }
}
