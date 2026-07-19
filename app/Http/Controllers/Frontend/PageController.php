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
            'about-us' => [
                'title' => 'About Us',
                'title_bn' => 'আমাদের সম্পর্কে',
                'content' => '<div class="text-center mb-5"><h2>Welcome to Our Foundation</h2><p class="lead">Building a Better Tomorrow Through Compassion and Service</p></div><h3>Who We Are</h3><p>Founded in 2010, we are a non-profit organization dedicated to serving the community through various welfare programs and initiatives. Our foundation has grown from a small group of passionate individuals to a thriving organization impacting thousands of lives across Bangladesh.</p><h3>Our Purpose</h3><p>We believe that every individual deserves access to education, healthcare, and opportunities for growth. Our mission is to create sustainable development programs that empower communities and transform lives.</p><h3>What We Do</h3><ul><li>Provide educational support and scholarships to underprivileged students</li><li>Organize health camps and medical assistance programs</li><li>Distribute food and essential supplies during emergencies</li><li>Support families in need through various welfare schemes</li><li>Promote community development and social harmony</li></ul><h3>Our Values</h3><p>Integrity, compassion, and dedication are the core values that guide our work. We are committed to transparency and accountability in all our operations.</p>',
            ],
            'mission-vision' => [
                'title' => 'Mission & Vision',
                'title_bn' => 'উদ্দেশ্য ও দৃষ্টিভঙ্গি',
                'content' => '<div class="text-center mb-5"><h2>Our Mission & Vision</h2><p class="lead">Guiding Principles That Drive Our Work</p></div><h3>Our Mission</h3><p>To create a better tomorrow by serving humanity today through compassion, integrity, and dedication. We strive to empower communities by providing access to education, healthcare, and economic opportunities.</p><h3>Our Vision</h3><p>To be the leading foundation in community development, ensuring every individual has access to opportunities for growth and prosperity. We envision a society where no one is left behind.</p><h3>Our Goals</h3><ul><li>Eliminate barriers to education for underprivileged children</li><li>Provide healthcare access to remote communities</li><li>Create sustainable livelihood opportunities</li><li>Build disaster-resilient communities</li><li>Foster social unity and harmony</li></ul>',
            ],
            'history' => [
                'title' => 'Our History',
                'title_bn' => 'আমাদের ইতিহাস',
                'content' => '<div class="text-center mb-5"><h2>Our Journey</h2><p class="lead">From Humble Beginnings to Impacting Thousands</p></div><h3>2010 - The Beginning</h3><p>Our foundation was established by a group of passionate individuals who believed in the power of community service. Starting with just 50 members, we began our journey of social welfare.</p><h3>2013 - First Major Milestone</h3><p>We launched our first major educational scholarship program, supporting 100 students from low-income families. This marked the beginning of our commitment to education.</p><h3>2016 - Expansion</h3><p>With growing support from donors and volunteers, we expanded our operations to 5 districts. Our health camp initiative served over 5,000 people in a single year.</p><h3>2019 - Recognition</h3><p>Our food distribution program during the pandemic earned recognition from the government. We distributed relief packages to over 10,000 families.</p><h3>2024 - Today</h3><p>Now with thousands of members and volunteers, we continue to grow and serve. Our impact spans across education, healthcare, and community development.</p>',
            ],
            'chairman-message' => [
                'title' => 'Chairman Message',
                'title_bn' => 'চেয়ারম্যানের বার্তা',
                'content' => '<div class="text-center mb-5"><img src="https://ui-avatars.com/api/?name=Chairman&size=150&background=4F46E5&color=fff" alt="Chairman" class="rounded-circle mb-3"><h3>MR. AHMED HOSSAIN</h3><p class="text-muted">Chairman</p></div><p>It is my honor to welcome you to our foundation. Since our inception, we have been committed to making a positive impact in our community.</p><p>Over the years, we have witnessed the transformative power of collective action. When we come together with a shared purpose, we can achieve remarkable things.</p><p>Our journey has been marked by resilience, dedication, and an unwavering commitment to our mission. Together, we have achieved remarkable milestones:</p><ul><li>Provided scholarships to over 500 students</li><li>Served healthcare to more than 15,000 community members</li><li>Distributed relief to thousands of families during crises</li></ul><p>I am confident that with your continued support, we will reach even greater heights. Every contribution, whether big or small, makes a difference in someone\'s life.</p><p>Thank you for being part of our journey. Together, we can build a better future for all.</p><p class="text-end"><strong>Warm regards,</strong><br>Ahmed Hossain<br>Chairman</p>',
            ],
            'contact' => [
                'title' => 'Contact Us',
                'title_bn' => 'যোগাযোগ করুন',
                'content' => '<div class="text-center mb-5"><h2>Get In Touch</h2><p class="lead">We would love to hear from you</p></div><div class="row"><div class="col-md-6"><h4>Contact Information</h4><p><i class="bi bi-geo-alt-fill text-primary me-2"></i> House 12, Road 5, Dhanmondi, Dhaka 1205</p><p><i class="bi bi-telephone-fill text-primary me-2"></i> +880 1700-000000</p><p><i class="bi bi-envelope-fill text-primary me-2"></i> info@foundation.org</p><p><i class="bi bi-clock-fill text-primary me-2"></i> Saturday - Thursday: 9:00 AM - 5:00 PM</p></div><div class="col-md-6"><h4>Send Us a Message</h4><form><div class="mb-3"><input type="text" class="form-control" placeholder="Your Name"></div><div class="mb-3"><input type="email" class="form-control" placeholder="Your Email"></div><div class="mb-3"><textarea class="form-control" rows="3" placeholder="Your Message"></textarea></div><button type="submit" class="btn btn-primary">Send Message</button></form></div></div>',
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
