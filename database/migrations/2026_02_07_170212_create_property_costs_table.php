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
        Schema::create('property_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cost_category_id')->constrained()->cascadeOnDelete();
            $table->string('concept')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('MXN');
            $table->string('periodicity')->nullable();
            $table->boolean('included')->default(false);
            $table->boolean('visible_public')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_costs');
    }
};
