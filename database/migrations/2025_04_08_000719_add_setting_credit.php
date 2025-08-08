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
        $data = [
            [
                'key' => 'exchange_credit_cost',
                'value' => 2000,
                'editable' => true,
                'value_type' => 'float',
            ],
            [
                'key' => 'exchange_credit_gain_amount',
                'value' => 100,
                'editable' => true,
                'value_type' => 'float',
            ]
        ];
        foreach ($data as $value) {
            \App\Models\Setting::create($value);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Setting::whereIn('key', ['exchange_credit_cost', 'exchange_credit_gain_amount'])->delete();
    }
};
