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
        Schema::create('course_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->uuid('course_id');
            $table->uuid('course_section_id')->nullable();
            $table->string('title');
            $table->string('link')->nullable();
            $table->text('description');
            $table->integer('order');
            $table->tinyInteger('intro')->default(0); // 0 locked , 1 opened;
            $table->tinyInteger('is_active');

            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('course_section_id')
                  ->references('id')
                  ->on('course_sections')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_details');
    }
};
