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
        Schema::create('payment_services_tbl', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("user_service_id")->nullable(false);
            $table->float("payment");
            $table->integer("method_id")->nullable(false);
            $table->string("note",250)->nullable(true);
            $table->integer("user_received")->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_services_tbl');
    }
};
