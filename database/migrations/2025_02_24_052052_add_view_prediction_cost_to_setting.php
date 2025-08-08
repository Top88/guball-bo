<?php

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $date = Carbon::now()->toDateTimeString();
        $data = [
            [
                'key' => 'cost_for_view_prediction_number_1',
                'value' => 30,
                'editable' => 1,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'key' => 'cost_for_view_prediction_number_2',
                'value' => 20,
                'editable' => 1,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'key' => 'cost_for_view_prediction_number_3',
                'value' => 10,
                'editable' => 1,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'key' => 'cost_for_view_prediction_number_4',
                'value' => 0,
                'editable' => 0,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'key' => 'cost_for_view_prediction_number_5',
                'value' => 0,
                'editable' => 0,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'key' => 'cost_for_view_prediction_number_6',
                'value' => 0,
                'editable' => 0,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'key' => 'cost_for_view_prediction_number_7',
                'value' => 0,
                'editable' => 0,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'key' => 'cost_for_view_prediction_number_8',
                'value' => 0,
                'editable' => 0,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'key' => 'cost_for_view_prediction_number_9',
                'value' => 0,
                'editable' => 0,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'key' => 'cost_for_view_prediction_number_10',
                'value' => 0,
                'editable' => 0,
                'value_type' => 'float',
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ];
        Setting::insert($data);
        Setting::where('key', 'cost_for_view_prediction')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn('key', [
            'cost_for_view_prediction_number_1',
            'cost_for_view_prediction_number_2',
            'cost_for_view_prediction_number_3',
            'cost_for_view_prediction_number_4',
            'cost_for_view_prediction_number_5',
            'cost_for_view_prediction_number_6',
            'cost_for_view_prediction_number_7',
            'cost_for_view_prediction_number_8',
            'cost_for_view_prediction_number_9',
            'cost_for_view_prediction_number_10',
        ])->delete();
    }
};
