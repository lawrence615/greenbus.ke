<?php

namespace App\Http\Controllers\Admin\Tour;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Interfaces\Tour\MainRepositoryInterface;

class MainController extends Controller
{
    public function __construct(private readonly MainRepositoryInterface $mainRepository)
    {
        throw new \Exception('Not implemented');
    }

    public function index()
    {
        throw new \Exception('Not implemented');
    }

    public function show()
    {
        throw new \Exception('Not implemented');
    }

    public function delete()
    {
        throw new \Exception('Not implemented');
    }

    public function toggleStatus()
    {
        throw new \Exception('Not implemented');
    }
}
