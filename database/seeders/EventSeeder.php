<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Annual General Meeting 2024',
                'title_bn' => 'বার্ষিক সাধারণ সভা ২০২৪',
                'description' => 'Join us for our annual general meeting to discuss the progress and future plans of our foundation.',
                'description_bn' => 'আমাদের ফাউন্ডেশনের অগ্রগতি এবং ভবিষ্যৎ পরিকল্পনা নিয়ে আলোচনা করতে আমাদের বার্ষিক সাধারণ সভায় যোগ দিন।',
                'event_type' => 'meeting',
                'start_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
                'location' => 'Foundation Hall, Dhaka',
                'location_bn' => 'ঢাকা, ফাউন্ডেশন হল',
                'max_attendees' => 100,
                'registration_required' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Community Health Camp',
                'title_bn' => 'সামাজিক স্বাস্থ্য শিবির',
                'description' => 'Free health checkup camp for all community members. Services include general checkup, eye checkup, and dental care.',
                'description_bn' => 'সকল সম্প্রদায়ের সদস্যদের জন্য বিনামূল্যে স্বাস্থ্য পরীক্ষা শিবির। সেবার মধ্যে রয়েছে সাধারণ পরীক্ষা, চোখ পরীক্ষা এবং দাঁতের যত্ন।',
                'event_type' => 'seminar',
                'start_date' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'location' => 'Community Center, Dhaka',
                'location_bn' => 'ঢাকা, কমিউনিটি সেন্টার',
                'max_attendees' => 200,
                'registration_required' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Blood Donation Camp',
                'title_bn' => 'রক্তদান শিবির',
                'description' => 'Join our blood donation camp and help save lives. Your donation can make a difference.',
                'description_bn' => 'আমাদের রক্তদান শিবিরে যোগ দিন এবং জীবন বাঁচাতে সাহায্য করুন। আপনার দান পার্থক্য তৈরি করতে পারে।',
                'event_type' => 'volunteer',
                'start_date' => Carbon::now()->addDays(60)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(60)->format('Y-m-d'),
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'location' => 'Red Crescent Hall, Dhaka',
                'location_bn' => 'ঢাকা, রেড ক্রিসেন্ট হল',
                'max_attendees' => 150,
                'registration_required' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Educational Workshop',
                'title_bn' => 'শিক্ষামূলক কর্মশালা',
                'description' => 'Workshop on skill development and career guidance for students and young professionals.',
                'description_bn' => 'ছাত্র-ছাত্রী এবং তরুণ পেশাদারদের জন্য দক্ষতা উন্নয়ন এবং কর্মজীবন নির্দেশনা কর্মশালা।',
                'event_type' => 'workshop',
                'start_date' => Carbon::now()->addDays(75)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(76)->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '15:00:00',
                'location' => 'Training Center, Dhaka',
                'location_bn' => 'ঢাকা, প্রশিক্ষণ কেন্দ্র',
                'max_attendees' => 50,
                'registration_required' => true,
                'is_active' => true,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                ['title' => $event['title']],
                $event
            );
        }

        $this->command->info('Events seeded successfully!');
    }
}
