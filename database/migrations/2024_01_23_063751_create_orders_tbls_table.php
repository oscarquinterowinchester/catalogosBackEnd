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
        Schema::create('orders_tbl', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger("customer_id")->nullable(true);
            $table->unsignedBigInteger("vendor_id")->nullable(false);
            $table->datetime("date")->nullable(false);
            $table->float("modified_price")->nullable(true);
            $table->unsignedTinyInteger('confirmed')->default(0);

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_tbl');
    }
};
