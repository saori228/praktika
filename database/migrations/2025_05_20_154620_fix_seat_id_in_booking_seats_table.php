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
        // Получаем информацию о внешних ключах
        $foreignKeys = DB::select(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = 'booking_seats'
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'
             AND CONSTRAINT_NAME LIKE '%seat_id%'"
        );

        // Удаляем внешний ключ, если он существует
        Schema::table('booking_seats', function (Blueprint $table) use ($foreignKeys) {
            foreach ($foreignKeys as $foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            }
        });

        // Изменяем тип колонки seat_id
        DB::statement('ALTER TABLE booking_seats MODIFY seat_id VARCHAR(100)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Изменяем тип колонки seat_id обратно на integer
        DB::statement('ALTER TABLE booking_seats MODIFY seat_id INT');

        // Восстанавливаем внешний ключ, если это необходимо
        // Примечание: в данном случае мы не восстанавливаем внешний ключ,
        // так как мы будем использовать строковые идентификаторы для seat_id
    }
};