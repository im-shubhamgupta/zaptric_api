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
        Schema::create('user_shipping_address', function (Blueprint $table) {
            $table->integer('id',11)->autoIncrement();
            $table->unsignedInteger('user_id');
            $table->string('city',150);
            $table->string('state',100);
            $table->string('pin',10);
            $table->string('address',255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_shipping_address');
    }
};
