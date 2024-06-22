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
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('address');
            $table->string('owner')->nullable();
            $table->string('telp')->nullable();
            $table->unsignedBigInteger('business_entity_id')->index('business_entity_id');
            $table->unsignedBigInteger('division_id')->index('division_id');
            $table->unsignedBigInteger('region_id')->index('region_id');
            $table->unsignedBigInteger('cluster_id')->index('cluster_id');
            $table->string('district');
            $table->string('photo_shop_sign')->nullable();
            $table->string('photo_front')->nullable();
            $table->string('photo_left')->nullable();
            $table->string('photo_right')->nullable();
            $table->string('photo_ktp')->nullable();
            $table->string('video')->nullable();
            $table->integer('limit')->nullable();
            $table->integer('radius')->nullable();
            $table->string('latlong')->nullable();
            $table->enum('status',['MAINTAIN','UNMAINTAIN','UNPRODUCTIVE']);
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

            // Add foreign key constraint with cascading delete
            $table->foreign('cluster_id')
                ->references('id')
                ->on('clusters')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
