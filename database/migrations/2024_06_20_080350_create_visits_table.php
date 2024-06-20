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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->timestamp('visit_date');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('outlet_id')->index();
            $table->string('visit_type');
            $table->string('latlong_in')->nullable();
            $table->string('latlong_out')->nullable();
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->text('visit_report')->nullable();
            $table->enum('transaction', ['YES', 'NO'])->nullable();
            $table->integer('visit_duration')->nullable();
            $table->text('picture_visit_in')->nullable();
            $table->text('picture_visit_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
