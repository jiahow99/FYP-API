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
        Schema::disableForeignKeyConstraints();

        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->unsignedDecimal('price', 10, 2)->default(0);
            $table->unsignedSmallInteger('stock')->default(0)->nullable();
            $table->string('brand_name')->nullable();
            $table->timestamps();
            // Foreign key
            $table->foreignId('category_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->index()->constrained()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
