<?php

namespace Database\Factories;

use App\Models\Code;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Code::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $forOptions = ['course', 'chapter', 'lesson'];

        return [
            'code' => Str::random(10),
            'for' => $this->faker->randomElement($forOptions),
            'number_of_uses' => $this->faker->numberBetween(1, 100),
            'expires_at' => $this->faker->dateTimeBetween('+1 week', '+1 year')->format('Y-m-d H:i:s'),
        ];
    }
}
