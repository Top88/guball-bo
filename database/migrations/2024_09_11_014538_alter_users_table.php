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
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id')->unique()->change();
            $table->string('email')->nullable()->change();
            $table->renameColumn('name', 'full_name');
            $table->string('nick_name')->after('full_name');
            $table->string('phone')->unique()->after('remember_token');
            $table->string('status')->after('phone');
            $table->float('points')->default(0)->after('status');
            $table->float('coins')->default(0)->after('points');
        });
    }
};
