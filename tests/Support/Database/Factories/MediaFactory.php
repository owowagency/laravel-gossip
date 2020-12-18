<?php

namespace OwowAgency\Gossip\Tests\Support\Database\Factories;

use OwowAgency\Gossip\Tests\Support\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'model_id' => rand(999, 99999),
            'model_type' => $this->faker->name,
            'collection_name' => $this->faker->name,
            'name' => $this->faker->name,
            'file_name' => $this->faker->name,
            'mime_type' => $this->faker->mimeType,
            'disk' => 'public',
            'size' => rand(999, 99999),
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
            'order_column' => null,
        ];
    }
}
