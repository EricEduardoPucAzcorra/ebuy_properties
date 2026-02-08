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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('type_property_id')->constrained();
            $table->foreignId('type_operation_id')->constrained();
            $table->foreignId('status_property_id')->constrained();
            $table->string('cadastral_code')->nullable()->unique()->default(null);
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->string('currency', 3)->default('MXN');
            $table->boolean('price_negotiable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_exclusive')->default(false);
            $table->enum('presentation', ['Nuevo', 'Usado', 'En contrucción','Intestado'])->nullable()->default(null);
            $table->enum('condition', ['Excelente', 'Buena','Para remodelar'])->nullable()->default(null);
            $table->enum('delivery', ['Inmediata', 'Indicaciones'])->nullable()->default(null);
            $table->string('delivery_obs')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
