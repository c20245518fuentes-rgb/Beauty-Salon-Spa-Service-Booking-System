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
        Schema::create('appointments', function (Blueprint $table) {
            $table->string('appointment_id', 20)->primary();
            $table->string('client_id', 20);
            $table->string('stylist_id', 20);
            $table->string('service_id', 20);
            $table->dateTime('appointment_datetime');
            $table->string('appointment_status', 30);
            $table->timestamps();

            $table->foreign('client_id')->references('client_id')->on('clients')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('stylist_id')->references('stylist_id')->on('stylists')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('service_id')->references('service_id')->on('services')->cascadeOnUpdate()->restrictOnDelete();
            $table->index('appointment_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
