<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Manel',
            'email' => 'm@ster',
            'password' => '$2y$10$ZWbpuj9rbjV7OIW5tC8vdusHi/jyKnk8CjeXqbqi9Ng09BT/bwWa.'
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
