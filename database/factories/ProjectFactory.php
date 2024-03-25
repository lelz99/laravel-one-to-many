<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->text(20);

        Storage::makeDirectory('project_images');
        $img = fake()->image(null, 360, 250);
        $img_url = Storage::putFile('project_images', $img);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(5, true),
            'preview_project' => $img_url,
            'end_date' => fake()->date(),
            'is_published' => fake()->boolean()
        ];
    }
}
