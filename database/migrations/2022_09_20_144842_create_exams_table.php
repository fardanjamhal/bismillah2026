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
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->uuid('user_id')->nullable();
            $table->uuid('exam_group_id')->nullable();
            $table->uuid('category_id');
            $table->uuid('lesson_category_id');
            $table->uuid('lesson_id');
            $table->uuid('question_title_id');
            $table->tinyInteger('question_selection_mode')->default(1);
            $table->integer('number_of_questions')->nullable();
            $table->string('title');
            $table->text('description')->nullable();

            $table->integer('duration')->nullable();
            $table->tinyInteger('random_question')->nullable();
            $table->tinyInteger('random_answer')->nullable();
            $table->tinyInteger('show_answer')->nullable();
            $table->tinyInteger('show_question_number_navigation')->nullable();
            $table->tinyInteger('show_question_number')->nullable();
            $table->tinyInteger('next_question_automatically')->nullable();
            $table->tinyInteger('show_prev_next_button')->nullable();
            $table->tinyInteger('type_option')->nullable();
            $table->tinyInteger('button_type_finish')->nullable();
            $table->double('percentage_grade')->nullable();
            $table->tinyInteger('repeat_the_exam')->nullable();
            $table->tinyInteger('show_ranking_exam')->nullable();
            $table->tinyInteger('show_answer_discussion')->default(0);
            $table->datetime('exam_start_time')->nullable();
            $table->datetime('exam_end_time')->nullable();
            $table->enum('exam_status', ['active', 'inactive', 'inprogress'])->default('active')->nullable();
            $table->datetime('release_date')->nullable();
            $table->integer('total_tolerance')->nullable();
            $table->string('unblock_token')->nullable();
            $table->tinyInteger('price_type')->nullable(); // 1 free, 2 paid
            $table->double('price_before_discount')->nullable();
            $table->double('price_after_discount')->nullable();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
                  
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('lesson_category_id')
                  ->references('id')
                  ->on('lesson_categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('lesson_id')
                  ->references('id')
                  ->on('lessons')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('question_title_id')
                  ->references('id')
                  ->on('question_titles')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('exam_group_id')
                  ->references('id')
                  ->on('exam_groups')
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
        Schema::dropIfExists('exams');
    }
};
