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
            if (!Schema::hasColumn('booking_seats', 'venue_zone_id')) {
                $table->unsignedBigInteger('venue_zone_id')->nullable()->after('booking_id');
                $table->foreign('venue_zone_id')->references('id')->on('venue_zones')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            if (Schema::hasColumn('booking_seats', 'venue_zone_id')) {
                $table->dropForeign(['venue_zone_id']);
                $table->dropColumn('venue_zone_id');
            }
        });
    }
};