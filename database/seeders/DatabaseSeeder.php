<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            GeneralSettingSeeder::class,
            CmsPageSeeder::class,
            MemberSeeder::class,
            ContributionSeeder::class,
            EventSeeder::class,
            NoticeSeeder::class,
            ActivitySeeder::class,
            AlbumSeeder::class,
        ]);
    }
}
