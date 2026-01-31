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
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->string('currency', 3)->default('MXN');
            //Se usara una ubicacion fija pero usaremos otro modelado llamado address
            // $table->string('address')->nullable();
            // $table->foreignId('country_id')->constrained('countries')->nullable();
            // $table->foreignId('state_id')->constrained('states')->nullable();
            // $table->foreign('city_id')->constrained('cities')->nullable();
            // $table->string('postal_code')->nullable();
            // $table->decimal('latitude', 10, 7)->nullable();
            // $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_active')->default(true);
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
