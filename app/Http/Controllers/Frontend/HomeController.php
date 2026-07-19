<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
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
                'site_tagline' => GeneralSetting::getSetting('site_tagline', 'Building a Better Tomorrow'),
            ],
            'aboutPage' => CmsPage::where('page_type', 'about')->where('status', true)->first(),
            'missionPage' => CmsPage::where('page_type', 'mission')->where('status', true)->first(),
        ];

        return view('frontend.home.index', $data);
    }

    public function about()
    {
        $locale = app()->getLocale();
        
        // Try to find about page
        $page = CmsPage::where('page_type', 'about')
            ->orWhere('slug', 'about-us')
            ->where('status', true)
            ->first();

        // If page exists, redirect to it
        if ($page) {
            return redirect()->route('frontend.page', $page->slug);
        }

        // Otherwise show default about page
        $data = [
            'title' => 'About Us',
            'page' => null,
            'site_name' => GeneralSetting::getSetting('site_name', 'Foundation Management System'),
        ];

        return view('frontend.home.about', $data);
    }

    public function contact()
    {
        $locale = app()->getLocale();
        
        // Try to find contact page
        $page = CmsPage::where('page_type', 'contact')
            ->orWhere('slug', 'contact')
            ->where('status', true)
            ->first();

        // If page exists, redirect to it
        if ($page) {
            return redirect()->route('frontend.page', $page->slug);
        }

        // Otherwise show contact info from settings
        $data = [
            'title' => 'Contact Us',
            'settings' => [
                'phone' => GeneralSetting::getSetting('phone', '+880 1700-000000'),
                'email' => GeneralSetting::getSetting('email', 'info@foundation.org'),
                'address' => GeneralSetting::getSetting('address', '123 Foundation Street, Dhaka, Bangladesh'),
            ],
        ];

        return view('frontend.home.contact', $data);
    }
}
