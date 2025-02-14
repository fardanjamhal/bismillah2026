<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('category_id')->nullable();
            $table->string('title');
            $table->integer('active_period');
            $table->enum('period_type',['day', 'month']);
            $table->double('price_before_discount');
            $table->double('price_after_discount');
            $table->text('description');
            $table->string('hexa_color_background')->nullable();
            $table->string('hexa_color_title')->nullable();
            $table->tinyInteger('is_active');

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
