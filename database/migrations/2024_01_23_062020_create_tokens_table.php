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
        Schema::create('tokens_tbl', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('token', 255)->unique();
            $table->unsignedBigInteger('user_fk');
            $table->dateTime('created');
            $table->smallInteger('type');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens_tbl');
    }
};