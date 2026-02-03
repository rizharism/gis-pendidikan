<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Models\EducationFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetaController extends Controller
{
    public function index()
    {
        return view('map.index');
    }
    
    public function getFacilities()
    {
        try {
            $facilities = EducationFacility::all();
            
            return response()->json([
                'success' => true,
                'data' => $facilities,
                'count' => $facilities->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getFacilitiesByJenjang($jenjang)
    {
        $validJenjang = ['sd', 'smp', 'sma', 'universitas'];
        
        if (!in_array($jenjang, $validJenjang)) {
            return response()->json([
                'success' => false,
                'message' => 'Jenjang tidak valid'
            ], 400);
        }
        
        try {
            $facilities = EducationFacility::where('klas', $jenjang)->get();
            
            return response()->json([
                'success' => true,
                'data' => $facilities,
                'count' => $facilities->count(),
                'jenjang' => $jenjang
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        if (empty($search)) {
            return response()->json([
                'success' => false,
                'message' => 'Pencarian tidak boleh kosong'
            ], 400);
        }
        
        try {
            $facilities = EducationFacility::where('name', 'like', "%{$search}%")
                ->limit(10)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $facilities,
                'count' => $facilities->count(),
                'query' => $search
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal pencarian: ' . $e->getMessage()
            ], 500);
        }
    }
}