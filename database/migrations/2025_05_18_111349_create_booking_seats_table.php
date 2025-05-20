<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Шаг 1: Сохраняем существующие данные (если они есть)
        $bookingSeats = [];
        if (Schema::hasTable('booking_seats')) {
            $bookingSeats = DB::table('booking_seats')->get()->toArray();
        }

        // Шаг 2: Удаляем существующую таблицу
        Schema::dropIfExists('booking_seats');

        // Шаг 3: Создаем таблицу заново с правильной структурой
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('venue_zone_id')->nullable();
            $table->string('seat_id', 100); // Строковый тип для виртуальных мест
            $table->decimal('price', 8, 2);
            $table->timestamps();

            // Добавляем внешний ключ для venue_zone_id
            $table->foreign('venue_zone_id')->references('id')->on('venue_zones')->onDelete('set null');
        });

        // Шаг 4: Восстанавливаем данные (если они были)
        foreach ($bookingSeats as $seat) {
            // Преобразуем seat_id в строку, если это необходимо
            $seatId = is_numeric($seat->seat_id) ? (string)$seat->seat_id : $seat->seat_id;
            
            DB::table('booking_seats')->insert([
                'id' => $seat->id,
                'booking_id' => $seat->booking_id,
                'venue_zone_id' => $seat->venue_zone_id ?? null,
                'seat_id' => $seatId,
                'price' => $seat->price,
                'created_at' => $seat->created_at,
                'updated_at' => $seat->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // В случае отката миграции, мы не можем восстановить предыдущую структуру
        // с внешним ключом на seats.id, так как seat_id теперь строка
        Schema::dropIfExists('booking_seats');
        
        // Создаем таблицу с базовой структурой
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->unsignedBigInteger('venue_zone_id')->nullable();
            $table->string('seat_id', 100);
            $table->decimal('price', 8, 2);
            $table->timestamps();
            
            $table->foreign('venue_zone_id')->references('id')->on('venue_zones')->onDelete('set null');
        });
    }
};