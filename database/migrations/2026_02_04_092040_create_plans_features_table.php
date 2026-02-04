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
        Schema::create('plans_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained();
            $table->foreignId('feature_plan_id')->constrained();
            $table->integer('mount')->nullable();
            $table->string('description')->nullable();
            $table->string('other_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans_features');
    }
};
