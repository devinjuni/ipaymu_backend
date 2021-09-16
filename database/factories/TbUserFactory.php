<?php

namespace Database\Factories;

use App\Models\TbUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
class TbUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TbUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_key"=>$this->faker->uuid(),
            "nama"=>$this->faker->name(),
            "pekerjaan"=>$this->faker->jobTitle(),
            "tgl_lahir"=>$this->faker->date(),
        ];
    }
}
