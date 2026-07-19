<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [
                'title' => 'Food Distribution Program',
                'title_bn' => 'খাদ্য বিতরণ কর্মসূচি',
                'description' => 'Distributed food packages to 200+ families in need during the holy month of Ramadan.',
                'description_bn' => 'পবিত্র রমজান মাসে ২০০+ পরিবারকে খাদ্য প্যাকেজ বিতরণ করা হয়েছে।',
                'activity_type' => 'food',
                'start_date' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'location' => 'Dhaka',
                'beneficiaries_count' => 200,
                'volunteers_count' => 25,
                'budget' => 150000,
                'status' => 'completed',
            ],
            [
                'title' => 'Free Medical Camp',
                'title_bn' => 'বিনামূল্যে মেডিকেল ক্যাম্প',
                'description' => 'Organized a free medical camp providing health checkups and medicines to underprivileged communities.',
                'description_bn' => 'প্রান্তিক সম্প্রদায়গুলিকে স্বাস্থ্য পরীক্ষা এবং ওষুধ প্রদানকারী একটি বিনামূল্যে মেডিকেল ক্যাম্প আয়োজন করা হয়েছে।',
                'activity_type' => 'medical',
                'start_date' => Carbon::now()->subDays(45)->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(45)->format('Y-m-d'),
                'location' => 'Gulshan, Dhaka',
                'beneficiaries_count' => 350,
                'volunteers_count' => 30,
                'budget' => 80000,
                'status' => 'completed',
            ],
            [
                'title' => 'Education Support Program',
                'title_bn' => 'শিক্ষা সহায়তা কর্মসূচি',
                'description' => 'Provided educational materials and scholarships to 50 meritorious students from low-income families.',
                'description_bn' => 'নিম্ন-আয়ের পরিবারের ৫০ জন মেধাবী ছাত্র-ছাত্রীকে শিক্ষা সামগ্রী এবং বৃত্তি প্রদান করা হয়েছে।',
                'activity_type' => 'education',
                'start_date' => Carbon::now()->subDays(60)->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(60)->format('Y-m-d'),
                'location' => 'Mirpur, Dhaka',
                'beneficiaries_count' => 50,
                'volunteers_count' => 15,
                'budget' => 200000,
                'status' => 'completed',
            ],
            [
                'title' => 'Blood Donation Drive',
                'title_bn' => 'রক্তদান কর্মসূচি',
                'description' => 'Successfully collected 100 units of blood for the national blood bank.',
                'description_bn' => 'জাতীয় রক্ত ব্যাংকের জন্য ১০০ ইউনিট রক্ত সংগ্রহ করা হয়েছে।',
                'activity_type' => 'medical',
                'start_date' => Carbon::now()->subDays(90)->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(90)->format('Y-m-d'),
                'location' => 'Dhanmondi, Dhaka',
                'beneficiaries_count' => 100,
                'volunteers_count' => 20,
                'budget' => 30000,
                'status' => 'completed',
            ],
        ];

        foreach ($activities as $activity) {
            Activity::updateOrCreate(
                ['title' => $activity['title']],
                $activity
            );
        }

        $this->command->info('Activities seeded successfully!');
    }
}
