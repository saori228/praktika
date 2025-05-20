<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventType::create(['name' => 'concert']);
        EventType::create(['name' => 'theater']);
        EventType::create(['name' => 'movie']);
    }
}