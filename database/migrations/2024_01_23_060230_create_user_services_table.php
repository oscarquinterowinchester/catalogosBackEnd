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
        Schema::create('user_services_tbl', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("service_id")->nullable(false);
            $table->integer("user_id")->nullable(false);
            $table->float('client_price');
            $table->datetime("start")->nullable(true);
            $table->datetime("end")->nullable(true);
            $table->string("note")->nullable(true);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_services_tbl');
    }
};
