<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'member_id' => 'FMS-' . date('Y') . '-' . str_pad(fake()->unique()->numberBetween(1000, 9999), 4, '0', STR_PAD_LEFT),
            'name' => fake()->name(),
            'name_bn' => fake()->name(),
            'father_name' => fake()->name('male'),
            'father_name_bn' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'mother_name_bn' => fake()->name('female'),
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'mobile' => '+880' . fake()->numberBetween(1000000000, 9999999999),
            'email' => fake()->unique()->safeEmail(),
            'national_id' => fake()->numerify('###########'),
            'passport_number' => fake()->numerify('########'),
            'iqama_number' => fake()->numerify('########'),
            'occupation' => fake()->jobTitle(),
            'occupation_bn' => fake()->jobTitle(),
            'designation' => fake()->randomElement(['Manager', 'Engineer', 'Teacher', 'Doctor', 'Businessman']),
            'company_name' => fake()->company(),
            'present_address' => fake()->address(),
            'present_address_bn' => fake()->address(),
            'permanent_address' => fake()->address(),
            'permanent_address_bn' => fake()->address(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => '+880' . fake()->numberBetween(1000000000, 9999999999),
            'emergency_contact_relation' => fake()->randomElement(['Father', 'Mother', 'Spouse', 'Sibling']),
            'join_date' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'member_type' => fake()->randomElement(['general', 'life', 'honorary', 'founder', 'associate']),
            'status' => true,
            'position' => fake()->randomElement(['member', 'executive', 'secretary', 'vice_president', 'president', 'advisor']),
            'nominee_name' => fake()->name(),
            'nominee_relation' => fake()->randomElement(['Father', 'Mother', 'Spouse', 'Sibling', 'Child']),
            'nominee_phone' => '+880' . fake()->numberBetween(1000000000, 9999999999),
            'referrer_member_id' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    public function lifeMember(): static
    {
        return $this->state(fn (array $attributes) => [
            'member_type' => 'life',
        ]);
    }

    public function executive(): static
    {
        return $this->state(fn (array $attributes) => [
            'position' => 'executive',
        ]);
    }
}
