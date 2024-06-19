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
        Schema::create('clusters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_entity_id')->index('business_entity_id');
            $table->unsignedBigInteger('division_id')->index('division_id');
            $table->unsignedBigInteger('region_id')->index('region_id');
            $table->string('name');
            $table->timestamps();


            // Add foreign key constraint with cascading delete
            $table->foreign('business_entity_id')
                ->references('id')
                ->on('business_entities')
                ->onDelete('cascade');

            // Add foreign key constraint with cascading delete
            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onDelete('cascade');

            // Add foreign key constraint with cascading delete
            $table->foreign('region_id')
                ->references('id')
                ->on('regions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clusters');
    }
};
