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
        Schema::table('rider', function (Blueprint $table) {
            $table->string('current_location')->nullable()->before('from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
