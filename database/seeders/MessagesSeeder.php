<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
{
    public function run(): void
    {
        Message::create([
            'user_id' => 1,
            'chat_id' => 1,
            'body'    => 'Hola!!',
            'is_read' => true
        ]);

        Message::create([
            'user_id' => 2,
            'chat_id' => 1,
            'body'    => 'Hola, buenos días',
            'is_read' => true
        ]);
        
        Message::create([
            'user_id' => 1,
            'chat_id' => 2,
            'body'    => 'Te has ganado un millón, sólo debes responder a este mensaje.',
        ]);

        Message::create([
            'user_id' => 3,
            'chat_id' => 1,
            'body'    => 'Mensaje de prueba',
        ]);

        Message::create([
            'user_id' => 1,
            'chat_id' => 1,
            'body'    => 'Prueba',
        ]);
    }
}
