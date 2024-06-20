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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('business_entity_id')->index('business_entity_id')->nullable();
            $table->unsignedBigInteger('division_id')->index('division_id')->nullable();
            $table->unsignedBigInteger('region_id')->index('region_id')->nullable();
            $table->unsignedBigInteger('cluser_id')->index('cluser_id')->nullable();
            $table->unsignedBigInteger('cluser_id2')->index('cluser_id2')->nullable();
            // $table->unsignedBigInteger('position_id')->index('position_id');
            // $table->unsignedBigInteger('position_id')->index('position_id');
            $table->unsignedBigInteger('tm_id')->index('tm_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
