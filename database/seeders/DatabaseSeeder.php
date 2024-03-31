<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Ricardo Zuno',
            'email' => 'zunosan.ricardo506@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        \App\Models\User::factory(10)->create();

        $this->call([
            ChatsSeeder::class,
            MessagesSeeder::class,
        ]);
    }
}
