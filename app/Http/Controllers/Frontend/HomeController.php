<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Home',
            'settings' => [
                'site_name' => GeneralSetting::getSetting('site_name', 'Foundation Management System'),
                'site_tagline' => GeneralSetting::getSetting('site_tagline', ''),
            ],
        ];

        return view('frontend.home.index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About Us',
        ];

        return view('frontend.home.about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact Us',
            'settings' => [
                'phone' => GeneralSetting::getSetting('phone', ''),
                'email' => GeneralSetting::getSetting('email', ''),
                'address' => GeneralSetting::getSetting('address', ''),
            ],
        ];

        return view('frontend.home.contact', $data);
    }
}
