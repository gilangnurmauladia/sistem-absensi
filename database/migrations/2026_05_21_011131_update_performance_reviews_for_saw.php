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
        Schema::table('performance_reviews', function (Blueprint $table) {
            if (Schema::hasColumn('performance_reviews', 'total_score')) {
                $table->dropColumn('total_score');
            }
 
            if (Schema::hasColumn('performance_reviews', 'punctuality')) {
                $table->renameColumn('punctuality', 'tardiness_score');
            }
            if (Schema::hasColumn('performance_reviews', 'attendance')) {
                $table->renameColumn('attendance', 'attendance_score');
            }
            if (Schema::hasColumn('performance_reviews', 'discipline')) {
                $table->renameColumn('discipline', 'responsibility_score');
            }
            if (Schema::hasColumn('performance_reviews', 'cleanliness')) {
                $table->renameColumn('cleanliness', 'cleanliness_score');
            }
            if (Schema::hasColumn('performance_reviews', 'friendliness')) {
                $table->renameColumn('friendliness', 'friendliness_score');
            }
        });
 
        Schema::table('performance_reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('performance_reviews', 'final_score')) {
                $table->decimal('final_score', 5, 2)->nullable()->after('friendliness_score');
            }
            if (!Schema::hasColumn('performance_reviews', 'rank')) {
                $table->integer('rank')->nullable()->after('final_score');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('performance_reviews', function (Blueprint $table) {
            if (Schema::hasColumn('performance_reviews', 'final_score')) {
                $table->dropColumn(['final_score', 'rank']);
            }
            
            if (Schema::hasColumn('performance_reviews', 'tardiness_score')) {
                $table->renameColumn('tardiness_score', 'punctuality');
            }
            if (Schema::hasColumn('performance_reviews', 'attendance_score')) {
                $table->renameColumn('attendance_score', 'attendance');
            }
            if (Schema::hasColumn('performance_reviews', 'responsibility_score')) {
                $table->renameColumn('responsibility_score', 'discipline');
            }
            if (Schema::hasColumn('performance_reviews', 'cleanliness_score')) {
                $table->renameColumn('cleanliness_score', 'cleanliness');
            }
            if (Schema::hasColumn('performance_reviews', 'friendliness_score')) {
                $table->renameColumn('friendliness_score', 'friendliness');
            }
        });
 
        Schema::table('performance_reviews', function (Blueprint $table) {
             $table->tinyInteger('total_score')->storedAs('punctuality + attendance + discipline + cleanliness + friendliness');
        });
    }
};
