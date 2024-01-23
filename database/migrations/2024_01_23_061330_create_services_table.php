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
        Schema::create('services_tbl', function (Blueprint $table) {
            $table->id();
            $table->string("service",200)->unique()->nullable(false);
            $table->smallInteger("user_type_fk");
            $table->float("price");
            $table->string("description",300)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_tbl');
    }
};
