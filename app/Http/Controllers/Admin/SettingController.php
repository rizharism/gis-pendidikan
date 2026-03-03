<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'app_name'                 => Setting::get('app_name', 'GIS Pendidikan'),
            'app_logo_path'            => Setting::get('app_logo_path'),
            'dev_mode'                 => Setting::get('dev_mode', '0'),
            'default_basemap'          => Setting::get('default_basemap', 'osm'),
            'layer_control_collapsed'  => Setting::get('layer_control_collapsed', '0'),
            'theme_mode'              => Setting::get('theme_mode', 'light'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name'                => 'required|string|max:255',
            'app_logo'                => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'dev_mode'                => 'sometimes|boolean',
            'default_basemap'         => 'required|in:osm,satellite,topographic',
            'layer_control_collapsed' => 'sometimes|boolean',
            'theme_mode'             => 'required|in:light,dark',
        ]);

        Setting::set('app_name', $request->app_name);
        Setting::set('dev_mode', $request->boolean('dev_mode') ? '1' : '0');
        Setting::set('default_basemap', $request->default_basemap);
        Setting::set('layer_control_collapsed', $request->boolean('layer_control_collapsed') ? '1' : '0');
        Setting::set('theme_mode', $request->theme_mode);

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('app_logo_path');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('app_logo')->store('logo', 'public');
            Setting::set('app_logo_path', $path);
        }

        return back()->with('status', 'Pengaturan berhasil disimpan.');
    }
}
