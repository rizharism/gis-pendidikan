<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EducationFacility;
use Illuminate\Support\Facades\DB;
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
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('school_code', 'like', "%{$search}%");
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

    public function show(EducationFacility $educationFacility)
    {
        $gallery = is_array($educationFacility->gallery)
            ? array_map(fn($path) => asset('storage/' . $path), $educationFacility->gallery)
            : [];

        return response()->json([
            'id'               => $educationFacility->id,
            'name'             => $educationFacility->name,
            'klas'             => $educationFacility->klas,
            'accreditation'    => $educationFacility->accreditation,
            'school_code'      => $educationFacility->school_code,
            'principal_name'   => $educationFacility->principal_name,
            'phone'            => $educationFacility->phone,
            'email'            => $educationFacility->email,
            'website'          => $educationFacility->website,
            'student_capacity' => $educationFacility->student_capacity,
            'teacher_count'    => $educationFacility->teacher_count,
            'opening_hours'    => $educationFacility->opening_hours,
            'video_url'        => $educationFacility->video_url,
            'address'          => $educationFacility->address,
            'description'      => $educationFacility->description,
            'gallery'          => $gallery,
            'latitude'         => $educationFacility->latitude,
            'longitude'        => $educationFacility->longitude,
            'edit_url'         => route('admin.education-facility.edit', $educationFacility->id),
        ]);
    }

    /**
     * Get shared validation rules.
     */
    private function validationRules($id = null): array
    {
        return [
            'name'             => 'required|string|max:255',
            'klas'             => 'required|in:sd,smp,sma,universitas',
            'address'          => 'required|string',
            'latlong'          => 'required|string',
            'description'      => 'required|string',
            'school_code'      => 'nullable|string|max:50|unique:education_facilities,school_code' . ($id ? ",$id" : ''),
            'accreditation'    => 'nullable|in:A,B,C,D',
            'principal_name'   => 'nullable|string|max:255',
            'phone'            => ['nullable', 'string', 'max:30', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'email'            => 'nullable|email|max:255',
            'website'          => 'nullable|url|max:255',
            'student_capacity' => 'nullable|integer|min:0',
            'teacher_count'    => 'nullable|integer|min:0',
            'video_url'        => 'nullable|url|max:500',
            'gallery'          => 'nullable|array',
            'gallery.*'        => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        try {
            DB::beginTransaction();

            $data = $request->except(['latlong', 'gallery', 'opening_hours_data']);

            // Split latlong into latitude and longitude
            $coords = explode(',', $request->latlong);
            $data['latitude'] = trim($coords[0]);
            $data['longitude'] = trim($coords[1]);

            // Handle gallery (multiple images)
            $galleryPaths = [];
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension    = $file->getClientOriginalExtension();
                    $cleanName    = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', $originalName));
                    $filename     = time() . '_' . uniqid() . '_' . $cleanName . '.' . $extension;
                    $stored       = $file->storeAs('facilities', $filename, 'public');
                    if ($stored) {
                        $galleryPaths[] = $stored;
                    }
                }
            }
            // Save as JSON array (model casts 'gallery' => 'array')
            $data['gallery'] = !empty($galleryPaths) ? array_values($galleryPaths) : null;

            // Handle opening hours
            if ($request->has('opening_hours_data') && $request->opening_hours_data !== '') {
                $decoded = json_decode($request->opening_hours_data, true);
                $data['opening_hours'] = is_array($decoded) ? $decoded : null;
            }

            EducationFacility::create($data);

            DB::commit();

            $redirectUrl = route('admin.education-facility');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'redirect_url' => $redirectUrl]);
            }
            return redirect($redirectUrl)->with('success', 'Data fasilitas berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menambahkan data: ' . $e->getMessage()], 500);
            }
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
        $request->validate($this->validationRules($educationFacility->id));

        try {
            DB::beginTransaction();

            $data = $request->except(['latlong', 'gallery', 'opening_hours_data', 'remove_gallery']);

            // Split latlong into latitude and longitude
            $coords = explode(',', $request->latlong);
            $data['latitude'] = trim($coords[0]);
            $data['longitude'] = trim($coords[1]);

            // Handle gallery: merge existing + new, remove deleted
            $existingGallery = is_array($educationFacility->gallery) ? $educationFacility->gallery : [];

            // Remove images marked for deletion
            if ($request->has('remove_gallery')) {
                $toRemove = (array) $request->input('remove_gallery', []);
                foreach ($toRemove as $path) {
                    Storage::disk('public')->delete($path);
                    $existingGallery = array_values(array_filter($existingGallery, fn ($p) => $p !== $path));
                }
            }

            // Add new uploaded images
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension    = $file->getClientOriginalExtension();
                    $cleanName    = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', $originalName));
                    $filename     = time() . '_' . uniqid() . '_' . $cleanName . '.' . $extension;
                    $stored       = $file->storeAs('facilities', $filename, 'public');
                    if ($stored) {
                        $existingGallery[] = $stored;
                    }
                }
            }

            // Save as JSON array (model casts 'gallery' => 'array')
            $data['gallery'] = !empty($existingGallery) ? array_values($existingGallery) : null;

            // Handle opening hours
            if ($request->has('opening_hours_data') && $request->opening_hours_data !== '') {
                $decoded = json_decode($request->opening_hours_data, true);
                $data['opening_hours'] = is_array($decoded) ? $decoded : null;
            }

            $educationFacility->update($data);

            DB::commit();

            $redirectUrl = route('admin.education-facility');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'redirect_url' => $redirectUrl]);
            }
            return redirect($redirectUrl)->with('success', 'Data fasilitas berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
            }
            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(EducationFacility $educationFacility)
    {
        try {
            DB::beginTransaction();

            // Delete all gallery files
            if ($educationFacility->gallery && is_array($educationFacility->gallery)) {
                foreach ($educationFacility->gallery as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            $educationFacility->delete();

            DB::commit();

            return redirect()->route('admin.education-facility')->with('success', 'Data fasilitas berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
