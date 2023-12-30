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
        Schema::create('part_tyre_size', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('stock')->default(0)->nullable();
            $table->foreignId('part_id')->references('id')->on('parts');
            $table->foreignId('size_id')->references('id')->on('tyre_sizes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_tyre_size');
    }
};
