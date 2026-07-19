<?php

namespace Database\Factories;

use App\Models\CmsPage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CmsPageFactory extends Factory
{
    protected $model = CmsPage::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);
        
        return [
            'title' => $title,
            'title_bn' => fake()->sentence(4),
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'content' => '<p>' . implode('</p><p>', fake()->paragraphs(5)) . '</p>',
            'content_bn' => '<p>' . implode('</p><p>', fake()->paragraphs(5)) . '</p>',
            'excerpt' => fake()->paragraph(),
            'excerpt_bn' => fake()->paragraph(),
            'image' => null,
            'icon' => fake()->randomElement(['bi bi-heart', 'bi bi-people', 'bi bi-shield', 'bi bi-star']),
            'position' => fake()->numberBetween(1, 100),
            'status' => true,
            'page_type' => fake()->randomElement(array_keys(CmsPage::PAGE_TYPES)),
            'meta_title' => $title,
            'meta_description' => fake()->paragraph(),
            'meta_keywords' => implode(', ', fake()->words(5)),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    public function about(): static
    {
        return $this->state(fn (array $attributes) => [
            'page_type' => 'about',
            'slug' => 'about-us',
            'title' => 'About Us',
        ]);
    }
}
