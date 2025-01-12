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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->unsignedBigInteger('sensor_id'); // Sensor ID to which the notification is related
            $table->boolean('seen')->default(false); // Whether the notification has been seen
            $table->timestamps();

            $table->foreign('sensor_id')->references('sensor_id')->on('sensors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
