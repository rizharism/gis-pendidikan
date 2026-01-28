<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EducationFacilityController extends Controller
{
    public function index()
    {
        return view('admin.education-facility.index');
    }

    public function create()
    {
        return view('admin.education-facility.form');
    }

    public function store()
    {
        return view('admin.education-facility.form');
    }
}
