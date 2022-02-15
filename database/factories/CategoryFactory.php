<?php

namespace Database\Factories;

use App\Dto\CategoryDto;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    public function setDto(CategoryDto $categoryDto): CategoryFactory
    {
        return $this->state(function () use ($categoryDto) {
            return [
                'organization_id' => $categoryDto->getOrganizationId(),
                'creator_id' => $categoryDto->getManagerId(),
                'name' => $categoryDto->getName(),
                'ar_name' => $categoryDto->getArName(),
                'description' => $categoryDto->getDescription(),
                'ar_description' => $categoryDto->getArDescription(),
                'parent_id' => $categoryDto->getParentId(),
                'is_available_online' => $categoryDto->isAvailableOnline(),
                'image' => $categoryDto->getImagePath(),
                'cover' => $categoryDto->getCoverPath(),
                'slug' => $categoryDto->getEnSlug()
            ];
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            //
            'organization_id' => null,
            'creator_id' => null,
            'name' => $this->faker->name,
            'ar_name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'ar_description' => $this->faker->sentence,
            'parent_id' => 0,
            'is_available_online' => $this->faker->boolean,
            'image' => $this->faker->imageUrl(),
            'cover' => $this->faker->imageUrl(),
            'slug' => $this->faker->slug,
        ];
    }
}
