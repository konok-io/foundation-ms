<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $this->authorize('settings.view');

        $groups = Setting::GROUPS;
        $settings = Setting::all()->groupBy('group');

        return view('admin.settings.index', [
            'title' => 'Settings',
            'page_title' => 'System Settings',
            'groups' => $groups,
            'settings' => $settings,
        ]);
    }

    public function general()
    {
        $this->authorize('settings.edit');

        $settings = Setting::getAllByGroup('general');

        return view('admin.settings.general', [
            'title' => 'General Settings',
            'page_title' => 'General Settings',
            'group' => 'general',
            'settings' => $settings,
        ]);
    }

    public function appearance()
    {
        $this->authorize('settings.edit');

        $settings = Setting::getAllByGroup('appearance');

        return view('admin.settings.appearance', [
            'title' => 'Appearance Settings',
            'page_title' => 'Appearance Settings',
            'group' => 'appearance',
            'settings' => $settings,
        ]);
    }

    public function email()
    {
        $this->authorize('settings.edit');

        $settings = Setting::getAllByGroup('email');

        return view('admin.settings.email', [
            'title' => 'Email Settings',
            'page_title' => 'Email Settings',
            'group' => 'email',
            'settings' => $settings,
        ]);
    }

    public function payment()
    {
        $this->authorize('settings.edit');

        $settings = Setting::getAllByGroup('payment');

        return view('admin.settings.payment', [
            'title' => 'Payment Settings',
            'page_title' => 'Payment Settings',
            'group' => 'payment',
            'settings' => $settings,
        ]);
    }

    public function social()
    {
        $this->authorize('settings.edit');

        $settings = Setting::getAllByGroup('social');

        return view('admin.settings.social', [
            'title' => 'Social Media Settings',
            'page_title' => 'Social Media Settings',
            'group' => 'social',
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorize('settings.edit');

        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                $setting->update(['value' => $value]);
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => $value,
                    'type' => $request->input("types.{$key}", 'text'),
                    'group' => $request->input('group', 'general'),
                ]);
            }
        }

        // Clear cache
        Cache::flush();

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }

    public function uploadImage(Request $request)
    {
        $this->authorize('settings.edit');

        $request->validate([
            'key' => 'required|string',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('settings', 'public');
        
        Setting::updateOrCreate(
            ['key' => $request->key],
            [
                'value' => $path,
                'type' => 'image',
                'group' => $request->input('group', 'appearance'),
            ]
        );

        Cache::flush();

        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }

    public function clearCache()
    {
        $this->authorize('settings.edit');

        Cache::flush();

        return redirect()->back()->with('success', 'Cache cleared successfully.');
    }
}
