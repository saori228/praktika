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
        // Проверяем существование внешнего ключа
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'booking_seats'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            AND CONSTRAINT_NAME LIKE '%seat_id%'
        ");

        // Если внешний ключ существует, удаляем его
        if (!empty($foreignKeys)) {
            foreach ($foreignKeys as $key) {
                Schema::table('booking_seats', function (Blueprint $table) use ($key) {
                    $table->dropForeign($key->CONSTRAINT_NAME);
                });
            }
        }

        // Изменяем тип колонки seat_id
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->string('seat_id', 100)->change();
        });

        // Проверяем наличие колонки venue_zone_id
        if (!Schema::hasColumn('booking_seats', 'venue_zone_id')) {
            Schema::table('booking_seats', function (Blueprint $table) {
                $table->unsignedBigInteger('venue_zone_id')->nullable()->after('booking_id');
                $table->foreign('venue_zone_id')->references('id')->on('venue_zones')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Если нужно вернуть всё обратно, но это может быть сложно
        // из-за потенциальной потери данных
    }
};