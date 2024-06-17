<?php

namespace Database\Factories;

use App\Enums\LinkStatus;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'original_url' => $this->faker->url,
            'shortened_url' => Str::random(5),
            'clicks' => $this->faker->numberBetween(0, 100),
            'status' => LinkStatus::CREATED->value,
        ];
    }
}
