<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // เปลี่ยนชื่อ table ด้านล่างให้ตรงกับของจริงใน DB: user_view_predictions หรือ user_view_prediction
        Schema::table('user_view_prediction', function (Blueprint $table) {
            // เพิ่มคอลัมน์ type ถ้ายังไม่มี (single | step)
            if (! Schema::hasColumn('user_view_prediction', 'type')) {
                $table->string('type', 20)->default('single')->after('target_user_id');
            }

            // สร้าง index เพื่อเช็คสิทธิ์แบบแยก single/step ได้เร็ว
            $table->index(['asking_user_id', 'target_user_id', 'type'], 'uvp_asking_target_type_idx');
        });
    }

    public function down(): void
    {
        Schema::table('user_view_prediction', function (Blueprint $table) {
            // ลบ index และคอลัมน์ ถ้ามีอยู่
            try { $table->dropIndex('uvp_asking_target_type_idx'); } catch (\Throwable $e) {}
            if (Schema::hasColumn('user_view_prediction', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
