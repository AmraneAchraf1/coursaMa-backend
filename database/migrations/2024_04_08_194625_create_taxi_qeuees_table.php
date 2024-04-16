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
        Schema::create('taxi_qeuees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('taxi_number');
            // taxi enter time
            $table->timestamp('enter_time')->default(now());
            // taxi exit time
            $table->timestamp('exit_time')->nullable();
            // taxi from
            $table->string('from');
            // taxi to
            $table->string('to');
            // number of passengers in the taxi max 6
            $table->integer('passengers')->default(0);
            // taxi status
            $table->string('status')->default('active');

            // soft delete
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxi_qeuees');
    }
};
