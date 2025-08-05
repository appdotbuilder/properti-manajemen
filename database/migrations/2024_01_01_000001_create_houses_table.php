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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('type');
            $table->decimal('land_area', 8, 2);
            $table->decimal('building_area', 8, 2);
            $table->enum('status', ['available', 'sold', 'occupied'])->default('available');
            $table->string('owner_name')->nullable();
            $table->string('owner_phone')->nullable();
            $table->date('handover_date')->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->string('block_unit');
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('status');
            $table->index('type');
            $table->index('block_unit');
            $table->index(['status', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};