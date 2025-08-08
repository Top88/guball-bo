<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $data = [
            [
                'key' => 'match_time_allow_to_start_predic',
                'value' => '10:00',
                'editable' => true,
                'value_type' => 'string',
            ],
            [
                'key' => 'match_time_allow_to_end_predic',
                'value' => '09:59',
                'editable' => true,
                'value_type' => 'string',
            ]
        ];
        Setting::insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn('key', ['match_time_allow_to_start_predic', 'match_time_allow_to_end_predic'])->delete();
    }
};
