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
                'site_tagline' => GeneralSetting::getSetting('site_tagline', ''),
            ],
            'aboutPage' => CmsPage::where('page_type', 'about')->where('status', true)->first(),
            'missionPage' => CmsPage::where('page_type', 'mission')->where('status', true)->first(),
        ];

        return view('frontend.home.index', $data);
    }

    public function about()
    {
        $locale = app()->getLocale();
        
        $page = CmsPage::where('page_type', 'about')
            ->orWhere('slug', 'about-us')
            ->where('status', true)
            ->first();

        if ($page) {
            return redirect()->route('frontend.page', $page->slug);
        }

        $data = [
            'title' => 'About Us',
            'page' => null,
        ];

        return view('frontend.home.about', $data);
    }

    public function contact()
    {
        $locale = app()->getLocale();
        
        $page = CmsPage::where('page_type', 'contact')
            ->orWhere('slug', 'contact')
            ->where('status', true)
            ->first();

        if ($page) {
            return redirect()->route('frontend.page', $page->slug);
        }

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
