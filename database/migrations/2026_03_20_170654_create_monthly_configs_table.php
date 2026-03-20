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
        Schema::create('monthly_configs', function (Blueprint $table) {
        $table->id();
        $table->decimal('current_fee', 10, 2)->default(50.00);
        $table->decimal('daily_transfer_limit', 15, 2)->default(10000.00);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_configs');
    }
};
