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
        Schema::create('noos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('business_entity_id')->index();
            $table->unsignedBigInteger('division_id')->index();
            $table->string('name');
            $table->text('address');
            $table->string('owner');
            $table->string('phone');
            $table->string('optional_phone')->nullable();
            $table->string('ktp_outlet');
            $table->string('district');
            $table->unsignedBigInteger('region_id')->index();
            $table->unsignedBigInteger('cluster_id')->index();
            $table->string('photo_shop_sign');
            $table->string('photo_front');
            $table->string('photo_left');
            $table->string('photo_right');
            $table->string('photo_ktp');
            $table->string('video');
            $table->string('oppo');
            $table->string('vivo');
            $table->string('realme');
            $table->string('samsung');
            $table->string('xiaomi');
            $table->string('fl');
            $table->string('latlong');
            $table->bigInteger('limit')->nullable();
            $table->enum('status', ['PENDING', 'CONFIRMED', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->string('created_by');
            $table->timestamp('rejected_at')->nullable();
            $table->string('rejected_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->string('confirmed_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('notes')->nullable();
            $table->foreignId('tm_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noos');
    }
};
