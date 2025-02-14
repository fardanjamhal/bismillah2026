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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('user_id');
            $table->uuid('referrer_id')->nullable();
            $table->uuid('category_id')->nullable();

            $table->string('item_type')->nullable(); // exam, exam_group, voucher, bundling
            $table->string('item_id')->nullable(); // id item terkait

            $table->string('code');
            $table->text('description');
            $table->string('payment_method');
            $table->double('total_payment');
            $table->string('payment_file')->nullable();
            $table->enum('transaction_status', ['pending', 'paid', 'failed', 'done', 'expired']);

            $table->integer('active_period')->nullable(); // month
            $table->enum('period_type', ['day', 'month'])->nullable();
            $table->date('expired_date')->nullable();
            $table->string('snap_token')->nullable();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('referrer_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

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
        Schema::dropIfExists('transactions');
    }
};
