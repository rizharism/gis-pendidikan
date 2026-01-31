<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EducationFacility;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => EducationFacility::count(),
            'sd' => EducationFacility::where('klas', 'sd')->count(),
            'smp' => EducationFacility::where('klas', 'smp')->count(),
            'sma' => EducationFacility::where('klas', 'sma')->count(),
            'univ' => EducationFacility::where('klas', 'universitas')->count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
