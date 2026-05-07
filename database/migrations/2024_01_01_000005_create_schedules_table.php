<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('shift_type', ['pagi', 'siang', 'libur']);
            $table->time('start_time')->nullable(); // Pagi: 07:00, Siang: 15:00
            $table->time('end_time')->nullable();   // Pagi: 15:00, Siang: 23:00
            $table->string('notes')->nullable();
            $table->timestamps();

            // Satu karyawan hanya punya satu jadwal per hari
            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
