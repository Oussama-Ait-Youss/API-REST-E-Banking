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
        Schema::create('accounts', function (Blueprint $table) {
        $table->id();
        $table->string('account_number')->unique();
        $table->string('type'); // COURANT, EPARGNE, MINEUR
        $table->string('status')->default('ACTIVE'); // ACTIVE, BLOCKED, CLOSED
        $table->decimal('balance', 15, 2)->default(0);
        $table->decimal('overdraft_limit', 15, 2)->nullable();
        $table->decimal('interest_rate', 5, 2)->nullable();
        
        $table->foreignId('guardian_id')->nullable()->constrained('users')->nullOnDelete();
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
