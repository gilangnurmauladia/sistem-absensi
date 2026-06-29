<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dibuat aman untuk MariaDB/Laragon. Tidak memakai renameColumn Laravel
        // karena bisa menghasilkan SQL "RENAME COLUMN" yang sering error di MariaDB.
        $renames = [
            'punctuality'  => 'tardiness_score',
            'attendance'   => 'attendance_score',
            'discipline'   => 'responsibility_score',
            'cleanliness'  => 'cleanliness_score',
            'friendliness' => 'friendliness_score',
        ];

        foreach ($renames as $old => $new) {
            if (Schema::hasColumn('performance_reviews', $old) && !Schema::hasColumn('performance_reviews', $new)) {
                DB::statement("ALTER TABLE performance_reviews CHANGE `$old` `$new` INT NOT NULL DEFAULT 0");
            }
        }

        if (Schema::hasColumn('performance_reviews', 'total_score')) {
            DB::statement('ALTER TABLE performance_reviews DROP COLUMN `total_score`');
        }

        if (!Schema::hasColumn('performance_reviews', 'final_score')) {
            DB::statement('ALTER TABLE performance_reviews ADD `final_score` DECIMAL(5,2) NULL AFTER `friendliness_score`');
        }

        if (!Schema::hasColumn('performance_reviews', 'rank')) {
            DB::statement('ALTER TABLE performance_reviews ADD `rank` INT NULL AFTER `final_score`');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('performance_reviews', 'rank')) {
            DB::statement('ALTER TABLE performance_reviews DROP COLUMN `rank`');
        }

        if (Schema::hasColumn('performance_reviews', 'final_score')) {
            DB::statement('ALTER TABLE performance_reviews DROP COLUMN `final_score`');
        }
    }
};
