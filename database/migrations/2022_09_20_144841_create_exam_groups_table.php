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
        Schema::create('exam_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->uuid('user_id')->nullable();
            $table->uuid('category_id');
            $table->uuid('lesson_category_id');
            $table->string('title');
            $table->text('description');

            $table->tinyInteger('exam_group_type');
            $table->tinyInteger('exam_group_navigation_mode')->nullable();
            $table->integer('duration')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('random_question')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('random_answer')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('show_answer')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('show_question_number_navigation')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('show_question_number')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('next_question_automatically')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('show_prev_next_button')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('type_option')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('button_type_finish')->nullable(); // required if exam_group_type == 1
            $table->tinyInteger('repeat_the_exam')->nullable();
            $table->tinyInteger('show_ranking_exam')->nullable();
            $table->integer('total_tolerance')->nullable();
            $table->string('unblock_token')->nullable();

            $table->tinyInteger('assessment_type');
            $table->tinyInteger('minimal_grade_type');
            $table->decimal('minimal_grade', 5, 2)->nullable();
            $table->text('description_grade_less_than_minimal')->nullable();
            $table->text('description_grade_more_than_minimal')->nullable();
            $table->double('smallest_value_limit')->nullable();
            $table->double('biggest_value_limit')->nullable();
            $table->tinyInteger('certificate_print_user')->default(0);
            $table->datetime('exam_start_time')->nullable();
            $table->datetime('exam_end_time')->nullable();
            $table->enum('exam_status', ['active', 'inactive', 'inprogress'])->default('active');
            $table->datetime('release_date')->nullable();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_groups');
    }
};
