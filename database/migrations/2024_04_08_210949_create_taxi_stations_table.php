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
        Schema::create('taxi_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // position of the station
            $table->string('latitude');
            $table->string('longitude');
            // station status
            $table->string('status')->default('active');
            // city of the station
            $table->string('city');
            // station address
            $table->string('address');
            // user who created the station
            $table->foreignId('user_id')->unique()->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxi_stations');
    }
};
