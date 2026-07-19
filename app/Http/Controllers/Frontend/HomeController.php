<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\GeneralSetting;
use App\Models\Event;
use App\Models\Notice;
use App\Models\Activity;
use App\Models\Album;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $upcomingEvents = Event::where('is_active', true)
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        $activeNotices = Notice::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expire_date')
                    ->orWhere('expire_date', '>=', now()->toDateString());
            })
            ->orderBy('priority', 'desc')
            ->orderBy('publish_date', 'desc')
            ->limit(4)
            ->get();

        $recentActivities = Activity::where('status', 'completed')
            ->orderBy('start_date', 'desc')
            ->limit(3)
            ->get();

        $featuredAlbums = Album::where('is_active', true)
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $aboutPage = CmsPage::where('page_type', 'about')
            ->where('status', true)
            ->first();

        $data = [
            'title' => 'Home',
            'settings' => [
                'site_name' => GeneralSetting::getSetting('site_name', 'Foundation Management System'),
                'site_tagline' => GeneralSetting::getSetting('site_tagline', 'Building a Better Tomorrow'),
            ],
            'upcomingEvents' => $upcomingEvents,
            'activeNotices' => $activeNotices,
            'recentActivities' => $recentActivities,
            'featuredAlbums' => $featuredAlbums,
            'aboutPage' => $aboutPage,
        ];

        return view('frontend.home.premium', $data);
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
