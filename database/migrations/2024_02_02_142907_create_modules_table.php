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
        Schema::create('modules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->uuid('category_id');
            $table->string('title');
            $table->string('thumbnail')->nullable();
            $table->text('description');
            $table->integer('order');
            $table->tinyInteger('price_type')->nullable(); // 1 free, 2 paid
            $table->double('price_before_discount')->nullable();
            $table->double('price_after_discount')->nullable();
            $table->enum('status', ['active', 'inactive', 'inprogress'])->default('active');
            
            $table->foreign('category_id')
                 ->references('id')
                 ->on('categories')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
