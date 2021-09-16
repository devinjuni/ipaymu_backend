<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TbUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         TbUser::factory(50)->create();
    }
}
