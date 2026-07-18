<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $locale = app()->getLocale();
        
        $page = CmsPage::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $relatedPages = CmsPage::where('status', true)
            ->where('id', '!=', $page->id)
            ->where('page_type', $page->page_type)
            ->limit(3)
            ->get();

        $data = [
            'title' => $locale === 'bn' && $page->title_bn ? $page->title_bn : $page->title,
            'page' => $page,
            'relatedPages' => $relatedPages,
            'locale' => $locale,
        ];

        return view('frontend.page.show', $data);
    }

    public function about()
    {
        $locale = app()->getLocale();
        
        $page = CmsPage::where('page_type', 'about')
            ->where('status', true)
            ->first();

        if (!$page) {
            $page = CmsPage::where('slug', 'about-us')
                ->where('status', true)
                ->first();
        }

        if (!$page) {
            abort(404);
        }

        $relatedPages = CmsPage::where('status', true)
            ->whereIn('page_type', ['mission', 'history', 'chairman', 'secretary'])
            ->limit(4)
            ->get();

        $data = [
            'title' => $locale === 'bn' && $page->title_bn ? $page->title_bn : $page->title,
            'page' => $page,
            'relatedPages' => $relatedPages,
            'locale' => $locale,
        ];

        return view('frontend.page.show', $data);
    }

    public function mission()
    {
        $locale = app()->getLocale();
        
        $page = CmsPage::where('page_type', 'mission')
            ->where('status', true)
            ->first();

        if (!$page) {
            $page = CmsPage::where('slug', 'mission-vision')
                ->where('status', true)
                ->first();
        }

        if (!$page) {
            abort(404);
        }

        $data = [
            'title' => $locale === 'bn' && $page->title_bn ? $page->title_bn : $page->title,
            'page' => $page,
            'relatedPages' => collect(),
            'locale' => $locale,
        ];

        return view('frontend.page.show', $data);
    }

    public function contact()
    {
        $locale = app()->getLocale();
        
        $page = CmsPage::where('page_type', 'contact')
            ->where('status', true)
            ->first();

        if (!$page) {
            $page = CmsPage::where('slug', 'contact')
                ->where('status', true)
                ->first();
        }

        if (!$page) {
            // Create default contact page data
            $data = [
                'title' => 'Contact Us',
                'page' => null,
                'relatedPages' => collect(),
                'locale' => $locale,
                'settings' => [
                    'address' => GeneralSetting::getSetting('address', ''),
                    'phone' => GeneralSetting::getSetting('phone', ''),
                    'email' => GeneralSetting::getSetting('email', ''),
                    'facebook' => GeneralSetting::getSetting('facebook', ''),
                    'twitter' => GeneralSetting::getSetting('twitter', ''),
                ],
            ];
            return view('frontend.home.contact', $data);
        }

        $data = [
            'title' => $locale === 'bn' && $page->title_bn ? $page->title_bn : $page->title,
            'page' => $page,
            'relatedPages' => collect(),
            'locale' => $locale,
        ];

        return view('frontend.page.show', $data);
    }
}
