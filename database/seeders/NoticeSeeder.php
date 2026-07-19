<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notice;
use Carbon\Carbon;

class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        $notices = [
            [
                'title' => 'Monthly Contribution Due Date',
                'title_bn' => 'মাসিক চাঁদা জমা দেওয়ার শেষ তারিখ',
                'content' => 'All members are requested to pay their monthly contributions by the 10th of every month. Late payments may incur a small penalty.',
                'content_bn' => 'সকল সদস্যদের প্রতি মাসের ১০ তারিখের মধ্যে তাদের মাসিক চাঁদা পরিশোধ করতে অনুরোধ করা হচ্ছে। বিলম্বিত পেমেন্টে ছোট জরিমানা হতে পারে।',
                'notice_type' => 'general',
                'priority' => 'high',
                'publish_date' => Carbon::now()->format('Y-m-d'),
                'expire_date' => Carbon::now()->addMonth()->format('Y-m-d'),
                'is_active' => true,
            ],
            [
                'title' => 'Annual General Meeting Announcement',
                'title_bn' => 'বার্ষিক সাধারণ সভার ঘোষণা',
                'content' => 'The Annual General Meeting will be held on the last Saturday of this month. All members are encouraged to attend.',
                'content_bn' => 'এই মাসের শেষ শনিবার বার্ষিক সাধারণ সভা অনুষ্ঠিত হবে। সকল সদস্যদের উপস্থিত থাকার জন্য উৎসাহিত করা হচ্ছে।',
                'notice_type' => 'meeting',
                'priority' => 'urgent',
                'publish_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'expire_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
                'is_active' => true,
            ],
            [
                'title' => 'New Member Registration Open',
                'title_bn' => 'নতুন সদস্য নিবন্ধন খোলা',
                'content' => 'We are now accepting new member registrations. Visit our office or apply online to become a member of our foundation.',
                'content_bn' => 'আমরা এখন নতুন সদস্য নিবন্ধন গ্রহণ করছি। আমাদের ফাউন্ডেশনের সদস্য হতে আমাদের অফিসে যান বা অনলাইনে আবেদন করুন।',
                'notice_type' => 'member',
                'priority' => 'normal',
                'publish_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'expire_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
                'is_active' => true,
            ],
            [
                'title' => 'Holiday Notice',
                'title_bn' => 'ছুটির বিজ্ঞপ্তি',
                'content' => 'Our office will remain closed on the upcoming public holiday. Normal services will resume the next working day.',
                'content_bn' => 'আমাদের অফিস আসন্ন সরকারি ছুটির দিনে বন্ধ থাকবে। পরবর্তী কর্মদিবসে স্বাভাবিক সেবা পুনরায় শুরু হবে।',
                'notice_type' => 'holiday',
                'priority' => 'low',
                'publish_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'expire_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'is_active' => true,
            ],
        ];

        foreach ($notices as $notice) {
            Notice::updateOrCreate(
                ['title' => $notice['title']],
                $notice
            );
        }

        $this->command->info('Notices seeded successfully!');
    }
}
