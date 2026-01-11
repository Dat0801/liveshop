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
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['flat', 'weight-based', 'amount-based'])->default('flat');
            $table->decimal('rate', 10, 2);
            $table->decimal('min_order', 10, 2)->nullable();
            $table->decimal('max_order', 10, 2)->nullable();
            $table->integer('min_weight')->nullable();
            $table->integer('max_weight')->nullable();
            $table->integer('processing_days_min')->default(1);
            $table->integer('processing_days_max')->default(5);
            $table->json('applicable_countries')->nullable();
            $table->json('applicable_regions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
