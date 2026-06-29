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
        Schema::create('cleanliness_checks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->date('check_date');
    $table->boolean('meja_bersih')->default(false);
    $table->boolean('lantai_bersih')->default(false);
    $table->boolean('peralatan_bersih')->default(false);
    $table->boolean('area_kerja_bersih')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cleanlinesss_checks');
    }
};
