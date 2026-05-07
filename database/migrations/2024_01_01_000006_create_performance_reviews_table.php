<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewed_by')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('month'); // 1-12
            $table->year('year');

            // Indikator penilaian: 1=Rendah, 2=Sedang, 3=Tinggi
            $table->tinyInteger('punctuality');   // Ketepatan waktu
            $table->tinyInteger('attendance');    // Kehadiran
            $table->tinyInteger('discipline');    // Kedisiplinan
            $table->tinyInteger('cleanliness');   // Kebersihan
            $table->tinyInteger('friendliness');  // Keramahan

            $table->tinyInteger('total_score')->storedAs('punctuality + attendance + discipline + cleanliness + friendliness');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Satu karyawan hanya punya satu penilaian per bulan per tahun
            $table->unique(['employee_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
