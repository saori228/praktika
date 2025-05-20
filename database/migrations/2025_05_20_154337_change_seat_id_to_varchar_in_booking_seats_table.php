<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            // Изменяем тип колонки seat_id с integer на varchar
            $table->string('seat_id', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            // Возвращаем тип колонки seat_id на integer
            $table->integer('seat_id')->change();
        });
    }
};