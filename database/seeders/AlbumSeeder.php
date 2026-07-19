<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Album;
use App\Models\GalleryImage;
use Carbon\Carbon;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        $albums = [
            [
                'name' => 'Annual Gathering 2024',
                'name_bn' => 'বার্ষিক সমাবেশ ২০২৪',
                'description' => 'Photos from our annual gathering and celebration event.',
                'description_bn' => 'আমাদের বার্ষিক সমাবেশ এবং উদযাপন অনুষ্ঠানের ছবি।',
                'album_type' => 'photo',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Medical Camp 2024',
                'name_bn' => 'মেডিকেল ক্যাম্প ২০২৪',
                'description' => 'Images from our free medical camp organized for the community.',
                'description_bn' => 'সম্প্রদায়ের জন্য আয়োজিত আমাদের বিনামূল্যে মেডিকেল ক্যাম্পের ছবি।',
                'album_type' => 'photo',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Education Program',
                'name_bn' => 'শিক্ষা কর্মসূচি',
                'description' => 'Photos from our educational support and scholarship distribution program.',
                'description_bn' => 'আমাদের শিক্ষা সহায়তা এবং বৃত্তি বিতরণ কর্মসূচির ছবি।',
                'album_type' => 'photo',
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Blood Donation Camp',
                'name_bn' => 'রক্তদান শিবির',
                'description' => 'Images from our blood donation drive.',
                'description_bn' => 'আমাদের রক্তদান কর্মসূচির ছবি।',
                'album_type' => 'photo',
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Community Events',
                'name_bn' => 'সামাজিক অনুষ্ঠান',
                'description' => 'Various community events and programs throughout the year.',
                'description_bn' => 'সারা বছর ধরে বিভিন্ন সামাজিক অনুষ্ঠান এবং কর্মসূচি।',
                'album_type' => 'photo',
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Foundation Introduction',
                'name_bn' => 'ফাউন্ডেশন পরিচিতি',
                'description' => 'Introduction video of our foundation.',
                'description_bn' => 'আমাদের ফাউন্ডেশনের পরিচিতি ভিডিও।',
                'album_type' => 'video',
                'status' => 'active',
                'is_featured' => true,
            ],
        ];

        foreach ($albums as $albumData) {
            $album = Album::updateOrCreate(
                ['name' => $albumData['name']],
                $albumData
            );

            // Add sample gallery images
            if ($album->wasRecentlyCreated) {
                for ($i = 1; $i <= 4; $i++) {
                    GalleryImage::create([
                        'album_id' => $album->id,
                        'title' => $album->name . ' - Image ' . $i,
                        'title_bn' => $album->name_bn . ' - ছবি ' . $i,
                        'image_path' => 'gallery/sample-' . $i . '.jpg',
                        'is_featured' => $i === 1,
                    ]);
                }
            }
        }

        $this->command->info('Albums and Gallery Images seeded successfully!');
    }
}
