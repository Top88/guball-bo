<?php

use App\Models\Setting;
use Carbon\Carbon;
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
        Schema::create('user_check_in', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->dateTime('checked_date');
            $table->timestamps();
        });
        Setting::create(['key' => 'start_check_in_date', 'value' => Carbon::now()->startOfDay()]);
        Setting::create(['key' => 'check_in_period', 'value' => 7]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_check_in');
        Setting::whereIn('key', ['start_check_in_date', 'check_in_period'])->delete();
    }
};
