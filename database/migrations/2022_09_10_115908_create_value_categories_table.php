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
        Schema::create('value_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->uuid('value_category_group_id')->nullable();
            $table->uuid('lesson_id');
            $table->string('name');
            $table->tinyInteger('assessment_type')->default(1); // 1 based on the number of values ​​per category,  2 based on the total value
            $table->tinyInteger('add_relative_score')->default(0);
            $table->tinyInteger('relative_score_formulation')->nullable();
            $table->tinyInteger('add_weighted_score')->default(0);
            $table->tinyInteger('weighted_score_formulation')->nullable();
            $table->integer('multiplier')->default(0);

            $table->foreign('lesson_id')
                  ->references('id')
                  ->on('lessons')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('value_category_group_id')
                  ->references('id')
                  ->on('value_category_groups')
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
        Schema::dropIfExists('value_categories');
    }
};
