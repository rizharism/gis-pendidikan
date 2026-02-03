<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EducationFacility;
use Illuminate\Support\Facades\Storage;

class EducationFacilityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $facilities = EducationFacility::latest()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.education-facility._table', [
                    'facilities' => $facilities,
                    'search' => $search
                ])->render(),
                'pagination' => $facilities->links()->render(),
                'info' => "Menampilkan {$facilities->firstItem()} - {$facilities->lastItem()} dari {$facilities->total()} data",
                'search' => $search
            ]);
        }

        return view('admin.education-facility.index', compact('facilities', 'search'));
    }

    public function create()
    {
        return view('admin.education-facility.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'klas' => 'required|in:sd,smp,sma,universitas',
            'address' => 'required|string',
            'latlong' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
        ]);

        try {
            $data = $request->all();

            // Split latlong into latitude and longitude
            $coords = explode(',', $request->latlong);
            $data['latitude'] = trim($coords[0]);
            $data['longitude'] = trim($coords[1]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                // Bersihkan nama file dari spasi dan karakter aneh
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', $originalName));

                $filename = time() . '_' . $cleanName . '.' . $extension;
                $data['image'] = $file->storeAs('facilities', $filename, 'public');
            }

            EducationFacility::create($data);

            return redirect()->route('admin.education-facility')->with('success', 'Data fasilitas berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function edit(EducationFacility $educationFacility)
    {
        return view('admin.education-facility.form', [
            'facility' => $educationFacility
        ]);
    }

    public function update(Request $request, EducationFacility $educationFacility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'klas' => 'required|in:sd,smp,sma,universitas',
            'address' => 'required|string',
            'latlong' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
        ]);

        try {
            $data = $request->all();

            // Split latlong into latitude and longitude
            $coords = explode(',', $request->latlong);
            $data['latitude'] = trim($coords[0]);
            $data['longitude'] = trim($coords[1]);

            if ($request->hasFile('image')) {
                // Delete old image
                if ($educationFacility->image) {
                    Storage::disk('public')->delete($educationFacility->image);
                }

                // New image with readable name
                $file = $request->file('image');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', $originalName));

                $filename = time() . '_' . $cleanName . '.' . $extension;
                $data['image'] = $file->storeAs('facilities', $filename, 'public');
            }

            $educationFacility->update($data);

            return redirect()->route('admin.education-facility')->with('success', 'Data fasilitas berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(EducationFacility $educationFacility)
    {
        try {
            if ($educationFacility->image) {
                Storage::disk('public')->delete($educationFacility->image);
            }
            $educationFacility->delete();

            return redirect()->route('admin.education-facility')->with('success', 'Data fasilitas berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
