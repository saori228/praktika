<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем администратора
        User::create([
            'name' => 'Админ',
            'surname' => 'Админов',
            'email' => 'sasha123no@gmail.com',
            'password' => Hash::make('12345678A'),
            'role' => 'admin', // Исправлено с 'roles' на 'role'
        ]);
        
        // Создаем организатора
        User::create([
            'name' => 'Александр',
            'surname' => 'Коротчук',
            'email' => 'korotchuk8673@gmail.com',
            'password' => Hash::make('851293271SID'),
            'role' => 'organizer',
        ]);
        
        // Создаем пользователя
        User::create([
            'name' => 'Никита',
            'surname' => 'Курашов',
            'email' => 'kurashovnikita8517@gmail.com',
            'password' => Hash::make('8235918KSJH'),
            'role' => 'user',
        ]);
    }
}

// natali123@gmail.com
// 7512UASFAgasu&