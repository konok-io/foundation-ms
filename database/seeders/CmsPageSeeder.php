<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsPage;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'title_bn' => 'আমাদের সম্পর্কে',
                'slug' => 'about-us',
                'page_type' => 'about',
                'excerpt' => 'Learn about our foundation and our mission to serve the community.',
                'excerpt_bn' => 'আমাদের ফাউন্ডেশন এবং সম্প্রদায়ের সেবা করার আমাদের লক্ষ্য সম্পর্কে জানুন।',
                'content' => '<h2>Welcome to Our Foundation</h2>
<p>We are dedicated to improving the lives of community members through comprehensive welfare programs, financial support, and community development initiatives.</p>
<h3>Our Story</h3>
<p>Our foundation has been serving the community for over a decade, providing assistance to those in need and creating opportunities for growth and development.</p>
<p>Through the generous support of our members and donors, we have been able to touch the lives of thousands of families and create lasting positive change in our community.</p>',
                'content_bn' => '<h2>আমাদের ফাউন্ডেশনে স্বাগতম</h2>
<p>আমরা ব্যাপক কল্যাণমূলক কর্মসূচি, আর্থিক সহায়তা এবং সম্প্রদায়ের উন্নয়ন উদ্যোগের মাধ্যমে সম্প্রদায়ের সদস্যদের জীবন উন্নত করতে নিবেদিত।</p>
<h3>আমাদের গল্প</h3>
<p>এক যুগেরও বেশি সময় ধরে আমাদের ফাউন্ডেশন সম্প্রদায়ের সেবা করে আসছে, প্রয়োজনে থাকা মানুষদের সহায়তা প্রদান করে এবং উন্নয়ন ও প্রবৃদ্ধির সুযোগ তৈরি করে।</p>',
                'icon' => 'bi bi-info-circle',
                'position' => 1,
                'status' => true,
            ],
            [
                'title' => 'Mission & Vision',
                'title_bn' => 'লক্ষ্য ও দৃষ্টি',
                'slug' => 'mission-vision',
                'page_type' => 'mission',
                'excerpt' => 'Our mission is to create positive change in the community through welfare programs.',
                'excerpt_bn' => 'কল্যাণমূলক কর্মসূচির মাধ্যমে সম্প্রদায়ে ইতিবাচক পরিবর্তন আনা আমাদের লক্ষ্য।',
                'content' => '<h2>Our Mission</h2>
<p>To improve the quality of life for underprivileged families through sustainable welfare programs, educational support, and healthcare assistance.</p>
<h3>Our Vision</h3>
<p>To build a self-sufficient community where every family has access to basic necessities, quality education, and healthcare facilities.</p>
<h3>Core Values</h3>
<ul>
<li><strong>Compassion:</strong> We approach every individual with empathy and understanding.</li>
<li><strong>Integrity:</strong> We maintain transparency and accountability in all our operations.</li>
<li><strong>Community:</strong> We believe in the power of collective action and unity.</li>
<li><strong>Excellence:</strong> We strive for the highest standards in everything we do.</li>
</ul>',
                'content_bn' => '<h2>আমাদের লক্ষ্য</h2>
<p>টেকসই কল্যাণমূলক কর্মসূচি, শিক্ষা সহায়তা এবং স্বাস্থ্যসেবা সহায়তার মাধ্যমে বঞ্চিত পরিবারগুলির জীবনযাত্রার মান উন্নত করা।</p>
<h3>আমাদের দৃষ্টি</h3>
<p>এমন একটি আত্মনির্ভরশীল সম্প্রদায় গড়ে তোলা যেখানে প্রতিটি পরিবারের মৌলিক প্রয়োজন, মানসম্মত শিক্ষা এবং স্বাস্থ্যসেবা সুবিধা রয়েছে।</p>',
                'icon' => 'bi bi-bullseye',
                'position' => 2,
                'status' => true,
            ],
            [
                'title' => 'Our History',
                'title_bn' => 'আমাদের ইতিহাস',
                'slug' => 'history',
                'page_type' => 'history',
                'excerpt' => 'A journey of compassion and service since our establishment.',
                'excerpt_bn' => 'আমাদের প্রতিষ্ঠার পর থেকে সহানুভূতি ও সেবার যাত্রা।',
                'content' => '<h2>Our Journey</h2>
<p>Founded in 2010, our foundation began with a small group of dedicated volunteers who believed in the power of community service.</p>
<h3>Key Milestones</h3>
<ul>
<li><strong>2010:</strong> Foundation established with 50 founding members</li>
<li><strong>2012:</strong> First major relief operation during natural disaster</li>
<li><strong>2015:</strong> Launch of monthly contribution system</li>
<li><strong>2018:</strong> Opened first community health center</li>
<li><strong>2020:</strong> COVID-19 relief program benefiting 5,000+ families</li>
<li><strong>2023:</strong> Reached milestone of 500 active members</li>
</ul>
<p>Today, we continue to grow and serve, guided by our founding principles of compassion, integrity, and community service.</p>',
                'content_bn' => '<h2>আমাদের যাত্রা</h2>
<p>২০১০ সালে প্রতিষ্ঠিত, আমাদের ফাউন্ডেশন সম্প্রদায় সেবায় বিশ্বাসী কয়েকজন নিবেদিত স্বেচ্ছাসেবকের একটি ছোট গ্রুপ দিয়ে শুরু হয়েছিল।</p>
<h3>মূল মাইলফলক</h3>
<ul>
<li><strong>২০১০:</strong> ৫০ জন প্রতিষ্ঠাতা সদস্য নিয়ে ফাউন্ডেশন প্রতিষ্ঠিত</li>
<li><strong>২০১২:</strong> প্রাকৃতিক দুর্যোগে প্রথম বড় ত্রাণ কার্যক্রম</li>
<li><strong>২০১৫:</strong> মাসিক অবদান ব্যবস্থা চালু</li>
<li><strong>২০২০:</strong> COVID-19 ত্রাণ কার্যক্রম</li>
</ul>',
                'icon' => 'bi bi-clock-history',
                'position' => 3,
                'status' => true,
            ],
            [
                'title' => 'Message from Chairman',
                'title_bn' => 'চেয়ারম্যানের বার্তা',
                'slug' => 'chairman-message',
                'page_type' => 'chairman',
                'excerpt' => 'A warm welcome message from our Chairman.',
                'excerpt_bn' => 'আমাদের চেয়ারম্যানের কাছ থেকে একটি উষ্ণ স্বাগত বার্তা।',
                'content' => '<h2>A Message from Our Chairman</h2>
<p>Dear Members, Partners, and Friends,</p>
<p>It is my honor and privilege to serve as the Chairman of this remarkable foundation. Our journey has been one of dedication, compassion, and unwavering commitment to serving our community.</p>
<p>Together, we have achieved remarkable milestones. From providing emergency relief during natural disasters to supporting education for underprivileged children, every achievement reflects our collective spirit and shared vision.</p>
<p>I extend my heartfelt gratitude to all our members, volunteers, and donors whose generosity makes our work possible. Your continued support inspires us to do more, reach further, and serve better.</p>
<p>Together, we can build a brighter future for our community.</p>
<p><strong>Warm regards,</strong><br>Chairman<br>Foundation Management System</p>',
                'content_bn' => '<h2>আমাদের চেয়ারম্যানের বার্তা</h2>
<p>প্রিয় সদস্য, অংশীদার এবং বন্ধুরা,</p>
<p>এই অসাধারণ ফাউন্ডেশনের চেয়ারম্যান হিসেবে দায়িত্ব পালন করা আমার সম্মান ও বিশেষাধিকার। আমাদের যাত্রা ছিল উৎসর্গ, সহানুভূতি এবং আমাদের সম্প্রদায়ের সেবায় অবিচল প্রতিশ্রুতির।</p>
<p>আপনাদের সকলের প্রতি আন্তরিক কৃতজ্ঞতা জ্ঞাপন করছি।</p>',
                'icon' => 'bi bi-person-badge',
                'position' => 4,
                'status' => true,
            ],
            [
                'title' => 'Message from General Secretary',
                'title_bn' => 'মহাসচিবের বার্তা',
                'slug' => 'secretary-message',
                'page_type' => 'secretary',
                'excerpt' => 'A message from our General Secretary.',
                'excerpt_bn' => 'আমাদের মহাসচিবের বার্তা।',
                'content' => '<h2>A Message from Our General Secretary</h2>
<p>Dear Esteemed Members,</p>
<p>As the General Secretary, I am proud to share the progress and achievements of our foundation. This year has been transformative, with new initiatives that have expanded our reach and impact.</p>
<p>Our monthly contribution system has enabled us to provide consistent support to those in need. The emergency fund has helped families during their toughest times. These programs succeed because of your active participation and generosity.</p>
<p>I encourage all members to continue their valuable contributions and to actively participate in our community activities. Together, we are making a real difference in people\'s lives.</p>
<p><strong>Best regards,</strong><br>General Secretary<br>Foundation Management System</p>',
                'content_bn' => '<h2>আমাদের মহাসচিবের বার্তা</h2>
<p>সম্মানিত সদস্যগণ,</p>
<p>মহাসচিব হিসেবে আমি আমাদের ফাউন্ডেশনের অগ্রগতি এবং অর্জনগুলি ভাগ করতে পেরে গর্বিত। এই বছরটি রূপান্তরমূলক হয়েছে।</p>',
                'icon' => 'bi bi-pen',
                'position' => 5,
                'status' => true,
            ],
            [
                'title' => 'Contact Information',
                'title_bn' => 'যোগাযোগের তথ্য',
                'slug' => 'contact',
                'page_type' => 'contact',
                'excerpt' => 'Get in touch with us for any queries or support.',
                'excerpt_bn' => 'যেকোনো প্রশ্ন বা সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।',
                'content' => '<h2>Contact Us</h2>
<p>We are here to help! Feel free to reach out to us for any queries, suggestions, or support.</p>
<h3>Office Address</h3>
<p>123 Foundation Street<br>Dhaka, Bangladesh</p>
<h3>Phone</h3>
<p>+880 1700-000000</p>
<h3>Email</h3>
<p>info@foundation.org</p>
<h3>Office Hours</h3>
<p>Sunday - Thursday: 9:00 AM - 5:00 PM<br>Friday - Saturday: Closed</p>',
                'content_bn' => '<h2>যোগাযোগ করুন</h2>
<p>আমরা সাহায্যের জন্য এখানে! যেকোনো প্রশ্ন, পরামর্শ বা সহায়তার জন্য আমাদের সাথে যোগাযোগ করতে দ্বিধা করবেন না।</p>',
                'icon' => 'bi bi-telephone',
                'position' => 6,
                'status' => true,
            ],
        ];

        foreach ($pages as $page) {
            CmsPage::firstOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }

        $this->command->info('CMS Pages seeded successfully!');
    }
}
