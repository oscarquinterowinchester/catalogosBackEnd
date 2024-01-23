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
        Schema::create('usuarios_tbl', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("correo",90)->unique()->nullable(false); 
            $table->datetime("email_verified_at")->nullable(true);
            $table->binary('password');
            // $table->integer('type',11)->nullable(false);
            $table->integer('type')->nullable(false);
            $table->unsignedBigInteger('company_fk')->nullable(true);
            $table->tinyInteger('estatus_fk')->default(1);
            $table->float('rating'); // 8 digitos en total, 2 decimales

            // $table->
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_tbl');
    }
};
