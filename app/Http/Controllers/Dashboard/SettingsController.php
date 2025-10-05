<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Show the edit page for a setting.
     */
    public function bulkEdit(): View
    {
        $settings = Setting::all();

        return view('dashboard.settings.edit', compact('settings'));
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*.value' => 'nullable|string',
            'settings.*.description' => 'nullable|string',
        ]);

        foreach ($data['settings'] as $id => $settingData) {
            $setting = Setting::find($id);
            if ($setting) {
                $setting->update([
                    'value' => $settingData['value'] ?? $setting->value,
                    'description' => $settingData['description'] ?? $setting->description,
                ]);
            }
        }

        return redirect()->route('settings.bulkEdit')->with('success', __('dashboard.settings.updated_successfully'));
    }
}
