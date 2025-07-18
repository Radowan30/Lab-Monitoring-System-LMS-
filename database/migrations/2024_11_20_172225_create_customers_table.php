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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customer_id');
            $table->string('full_name', 100);
            $table->string('passport_number', 20);
            $table->string('institution', 100)->nullable();
            $table->string('specific_institution', 100)->nullable(); // This is the new column
            $table->string('position', 255)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->dateTime('entry_datetime');
            $table->dateTime('exit_datetime')->nullable();
            $table->string('purpose_of_usage', 255)->nullable();
            $table->string('equipment_used', 255)->nullable();
            $table->string('type_of_analysis', 100)->nullable();
            $table->string('supervisor_name', 100)->nullable();
            $table->decimal('usage_duration', 5, 2)->nullable();
            $table->text('suggestions')->nullable();
            $table->text('purpose_description')->nullable();
            $table->text('technical_issues')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};