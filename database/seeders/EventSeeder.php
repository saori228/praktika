<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\VenueZone;
use App\Models\Seat;
use App\Models\User;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizer = User::where('role', 'organizer')->first();
        
        // Создаем концерты для артистов
        $this->createConcert(
            'Концерт Три дня дождя',
            'Проект «Три дня дождя» во главе с бессменным фронтменом и основным автором песен Глебом Викторовым — одно из самых ярких явлений российской сцены 2020-х годов, послуживший возрождению отечественной рок-музыки.',
            '2025-06-18 20:00:00',
            1, // ID артиста
            $organizer->id,
            '/images/events/tri-dnya-dozhdya-concert.jpg'
        );
        
        $this->createConcert(
            'Концерт OG Buda',
            'OG Buda — один из лидеров современной волны рэпа в России. Молодой и дерзкий, со свойственным ему вайбом, харизмой и прямолинейностью.',
            '2025-06-22 20:00:00',
            2, // ID артиста
            $organizer->id,
            '/images/events/og-buda-concert.jpg'
        );
        
        $this->createConcert(
            'Концерт SQWOZ BAB',
            'Марат SQWOZ BAB — единственный в мире артист с огромным количеством лысеющих братьев, автор гимна троечников и человек, переосмысливший анекдот про тракториста.',
            '2025-07-26 20:00:00',
            3, // ID артиста
            $organizer->id,
            '/images/events/sqwoz-bab-concert.jpg'
        );
        
        $this->createConcert(
            'Концерт Хаски',
            'Главный пройдоха русского рэпа везет с собой большую сольную программу, где новое творчество встречает полюбившиеся хиты-уховертки.',
            '2025-08-09 20:00:00',
            4, // ID артиста
            $organizer->id,
            '/images/events/husky-concert.jpg'
        );
        
        $this->createConcert(
            'Концерт OFFSET',
            'Offset является одним из самых влиятельных номинированных на премию «Грэмми» рэп-икон с Юга США. Шоумен, превращающий концерт в театральное представление, он известен своим мощнейшим флоу и яркими гимнами.',
            '2025-09-15 20:00:00',
            5, // ID артиста
            $organizer->id,
            '/images/events/offset-concert.jpg'
        );
        
        // Создаем театральную постановку
        $this->createTheater(
            'Последняя сказка',
            'Мюзикл, который переносит зрителей в мир сказочного Востока с роскошными дворцами, колоритными базарами, величественным караваном и полётом на ковре-самолёте.',
            '2025-07-10 19:00:00',
            $organizer->id,
            '/images/events/theater.jpg'
        );
        
        // Создаем фильм
        $this->createMovie(
            'Фильм ОЧИ',
            'Девочка Юрайя родилась на гористом острове посреди океана. В здешних дремучих лесах водится много диких зверей, но самыми опасными считаются загадочные существа очи. Одни считают, что это лишь сказка для детей, другие — что это враждебные создания. Однажды Юрайя находит в лесу потерявшегося детеныша очи и решает вернуть его родителям. Девочке придется найти общий язык с этим необычным обитателем леса и доказать людям, что дружить с очи лучше, чем их бояться.',
            '2025-08-01 18:00:00',
            $organizer->id,
            '/images/events/movie.jpg'
        );
    }
    
    private function createConcert($name, $description, $eventDate, $artistId, $userId, $imagePath)
    {
        $event = Event::create([
            'name' => $name,
            'description' => $description,
            'event_date' => $eventDate,
            'event_type_id' => 1, // Концерт
            'artist_id' => $artistId,
            'image_path' => $imagePath,
            'user_id' => $userId,
        ]);
        
        // Создаем зоны для концерта
        $dancefloor = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Танцпол',
            'price' => 2500,
            'capacity' => 5000, // Добавляем вместимость
        ]);
        
        $premium = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Премиум',
            'price' => 3500,
            'capacity' => 200, // Добавляем вместимость
        ]);
        
        $vip = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'VIP зона',
            'price' => 4500,
            'capacity' => 1000, // Добавляем вместимость
        ]);
        
        // Создаем места для танцпола (5000 мест)
        for ($i = 0; $i < 5000; $i++) {
            Seat::create([
                'venue_zone_id' => $dancefloor->id,
                'row' => floor($i / 100) + 1,
                'number' => ($i % 100) + 1,
                'is_available' => true,
            ]);
        }
        
        // Создаем места для премиум зоны (200 мест)
        for ($i = 0; $i < 200; $i++) {
            Seat::create([
                'venue_zone_id' => $premium->id,
                'row' => floor($i / 10) + 1,
                'number' => ($i % 10) + 1,
                'is_available' => true,
            ]);
        }
        
        // Создаем места для VIP зоны (1000 мест)
        for ($i = 0; $i < 1000; $i++) {
            Seat::create([
                'venue_zone_id' => $vip->id,
                'row' => floor($i / 50) + 1,
                'number' => ($i % 50) + 1,
                'is_available' => true,
            ]);
        }
    }
    
    private function createTheater($name, $description, $eventDate, $userId, $imagePath)
    {
        $event = Event::create([
            'name' => $name,
            'description' => $description,
            'event_date' => $eventDate,
            'event_type_id' => 2, // Театр
            'artist_id' => null,
            'image_path' => $imagePath,
            'user_id' => $userId,
        ]);
        
        // Создаем зоны для театра
        $regular = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Обычные места',
            'price' => 250,
            'capacity' => 150, // Добавляем вместимость (10 рядов по 15 мест)
        ]);
        
        $balcony = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Второй этаж',
            'price' => 500,
            'capacity' => 25, // Добавляем вместимость
        ]);
        
        // Создаем обычные места (10 рядов по 15 мест)
        for ($row = 1; $row <= 10; $row++) {
            for ($seat = 1; $seat <= 15; $seat++) {
                Seat::create([
                    'venue_zone_id' => $regular->id,
                    'row' => $row,
                    'number' => $seat,
                    'is_available' => true,
                ]);
            }
        }
        
        // Создаем места на втором этаже (25 мест)
        for ($i = 1; $i <= 25; $i++) {
            Seat::create([
                'venue_zone_id' => $balcony->id,
                'row' => 1,
                'number' => $i,
                'is_available' => true,
            ]);
        }
    }
    
    private function createMovie($name, $description, $eventDate, $userId, $imagePath)
    {
        $event = Event::create([
            'name' => $name,
            'description' => $description,
            'event_date' => $eventDate,
            'event_type_id' => 3, // Кино
            'artist_id' => null,
            'image_path' => $imagePath,
            'user_id' => $userId,
        ]);
        
        // Создаем зоны для кинотеатра
        $regular = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'Обычные места',
            'price' => 250,
            'capacity' => 150, // Добавляем вместимость (10 рядов по 15 мест)
        ]);
        
        $vip = VenueZone::create([
            'event_id' => $event->id,
            'name' => 'VIP места',
            'price' => 450,
            'capacity' => 20, // Добавляем вместимость (2 ряда по 5 мест с каждой стороны)
        ]);
        
        // Создаем обычные места (10 рядов по 15 мест)
        for ($row = 1; $row <= 10; $row++) {
            for ($seat = 1; $seat <= 15; $seat++) {
                Seat::create([
                    'venue_zone_id' => $regular->id,
                    'row' => $row,
                    'number' => $seat,
                    'is_available' => true,
                ]);
            }
        }
        
        // Создаем VIP места (2 ряда по 5 мест с каждой стороны)
        for ($row = 1; $row <= 2; $row++) {
            for ($side = 0; $side <= 1; $side++) {
                for ($seat = 1; $seat <= 5; $seat++) {
                    Seat::create([
                        'venue_zone_id' => $vip->id,
                        'row' => $row,
                        'number' => $side * 10 + $seat, // Левая сторона: 1-5, правая сторона: 11-15
                        'is_available' => true,
                    ]);
                }
            }
        }
    }
}