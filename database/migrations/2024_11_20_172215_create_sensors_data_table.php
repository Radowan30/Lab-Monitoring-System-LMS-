<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensors_data', function (Blueprint $table) {
            $table->id('data_id');
            $table->unsignedBigInteger('sensor_id');
            $table->decimal('temperature', 4, 2);
            $table->decimal('humidity', 3, 1);
            $table->dateTime('recorded_at');
            $table->timestamps();

            $table->foreign('sensor_id')->references('sensor_id')->on('sensors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors_data');
    }
};
