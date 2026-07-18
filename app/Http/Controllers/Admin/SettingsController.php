<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $this->authorize('settings.view');

        $data = [
            'title' => 'Settings',
            'page_title' => 'System Settings',
            'settings' => GeneralSetting::all()->groupBy('group'),
            'groups' => [
                'general' => 'General Settings',
                'contact' => 'Contact Information',
                'social' => 'Social Media Links',
                'appearance' => 'Appearance',
                'email' => 'Email Settings',
                'payment' => 'Payment Settings',
            ],
        ];

        return view('admin.settings.index', $data);
    }

    public function update(Request $request)
    {
        $this->authorize('settings.update');

        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'locale' => 'nullable|string|max:10',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $settingsToUpdate = [
                'site_name' => ['value' => $request->site_name, 'type' => 'text', 'group' => 'general'],
                'site_tagline' => ['value' => $request->site_tagline, 'type' => 'text', 'group' => 'general'],
                'site_description' => ['value' => $request->site_description, 'type' => 'textarea', 'group' => 'general'],
                'address' => ['value' => $request->address, 'type' => 'textarea', 'group' => 'contact'],
                'phone' => ['value' => $request->phone, 'type' => 'text', 'group' => 'contact'],
                'email' => ['value' => $request->email, 'type' => 'email', 'group' => 'contact'],
                'currency' => ['value' => $request->currency, 'type' => 'text', 'group' => 'general'],
                'currency_symbol' => ['value' => $request->currency_symbol, 'type' => 'text', 'group' => 'general'],
                'timezone' => ['value' => $request->timezone, 'type' => 'select', 'group' => 'general'],
                'locale' => ['value' => $request->locale, 'type' => 'select', 'group' => 'general'],
                'facebook' => ['value' => $request->facebook, 'type' => 'url', 'group' => 'social'],
                'twitter' => ['value' => $request->twitter, 'type' => 'url', 'group' => 'social'],
                'instagram' => ['value' => $request->instagram, 'type' => 'url', 'group' => 'social'],
                'linkedin' => ['value' => $request->linkedin, 'type' => 'url', 'group' => 'social'],
                'youtube' => ['value' => $request->youtube, 'type' => 'url', 'group' => 'social'],
                'primary_color' => ['value' => $request->primary_color, 'type' => 'color', 'group' => 'appearance'],
                'secondary_color' => ['value' => $request->secondary_color, 'type' => 'color', 'group' => 'appearance'],
            ];

            foreach ($settingsToUpdate as $key => $value) {
                GeneralSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value['value'],
                        'type' => $value['type'],
                        'group' => $value['group'],
                    ]
                );
            }

            // Handle file uploads
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('settings', 'public');
                GeneralSetting::updateOrCreate(
                    ['key' => 'logo'],
                    ['value' => $logoPath, 'type' => 'file', 'group' => 'appearance']
                );
            }

            if ($request->hasFile('favicon')) {
                $faviconPath = $request->file('favicon')->store('settings', 'public');
                GeneralSetting::updateOrCreate(
                    ['key' => 'favicon'],
                    ['value' => $faviconPath, 'type' => 'file', 'group' => 'appearance']
                );
            }

            // Clear cache
            Cache::flush();

            Log::info('Settings updated', ['updated_by' => auth()->id()]);

            DB::commit();

            return redirect()->back()->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        $this->authorize('settings.update');

        Cache::flush();

        Log::info('Cache cleared', ['cleared_by' => auth()->id()]);

        return redirect()->back()->with('success', 'Cache cleared successfully.');
    }

    public function activityLogs(Request $request)
    {
        $this->authorize('settings.view');

        $query = \App\Models\ActivityLog::with('causer');

        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('causer') && $request->causer) {
            $query->where('causer_id', $request->causer);
        }

        $data = [
            'title' => 'Activity Logs',
            'page_title' => 'Activity Logs',
            'activities' => $query->latest()->paginate(20),
            'users' => \App\Models\User::all(),
        ];

        return view('admin.settings.activity-logs', $data);
    }
}
