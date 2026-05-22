<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TenancyDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('tenancy/pages/TenancyDashboard');
    }
}
