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
            $table->tinyInteger('month');
            $table->year('year');

            // Skor akhir yang dipakai oleh controller dan view
            $table->integer('attendance_score')->default(0);
            $table->integer('tardiness_score')->default(0);
            $table->integer('responsibility_score')->default(0);
            $table->integer('cleanliness_score')->default(0);
            $table->integer('friendliness_score')->default(0);
            $table->decimal('final_score', 5, 2)->nullable();
            $table->integer('rank')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
