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
        
        // Try to find the page
        $page = CmsPage::where('slug', $slug)
            ->where('status', true)
            ->first();

        // If page doesn't exist, show a default page
        if (!$page) {
            return $this->showDefaultPage($slug, $locale);
        }

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

    private function showDefaultPage($slug, $locale)
    {
        $defaultPages = [
            'mission-vision' => [
                'title' => 'Mission & Vision',
                'title_bn' => 'উদ্দেশ্য ও দৃষ্টিভঙ্গি',
                'content' => '<p>Our mission is to empower communities through education, welfare, and sustainable development programs.</p><h3>Our Mission</h3><p>To create a better tomorrow by serving humanity today through compassion, integrity, and dedication.</p><h3>Our Vision</h3><p>To be the leading foundation in community development, ensuring every individual has access to opportunities for growth and prosperity.</p>',
            ],
            'history' => [
                'title' => 'Our History',
                'title_bn' => 'আমাদের ইতিহাস',
                'content' => '<p>Founded in 2010, our foundation has been dedicated to serving the community for over a decade.</p><p>Through the years, we have grown from a small community group to a prominent organization impacting thousands of lives.</p><p>Our journey has been marked by resilience, dedication, and an unwavering commitment to our mission.</p>',
            ],
            'chairman-message' => [
                'title' => 'Chairman Message',
                'title_bn' => 'চেয়ারম্যানের বার্তা',
                'content' => '<p>It is my honor to welcome you to our foundation. Since our inception, we have been committed to making a positive impact in our community.</p><p>Together, we have achieved remarkable milestones, and I am confident that with your continued support, we will reach even greater heights.</p><p>Thank you for being part of our journey.</p><p><strong>Warm regards,<br>Chairman</strong></p>',
            ],
        ];

        if (isset($defaultPages[$slug])) {
            $pageData = $defaultPages[$slug];
            $data = [
                'title' => $locale === 'bn' ? $pageData['title_bn'] : $pageData['title'],
                'page' => (object) [
                    'title' => $pageData['title'],
                    'title_bn' => $pageData['title_bn'],
                    'slug' => $slug,
                    'content' => $pageData['content'],
                ],
                'relatedPages' => collect(),
                'locale' => $locale,
            ];
            return view('frontend.page.show', $data);
        }

        abort(404);
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
