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
        Schema::create('member_category_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->uuid('user_id');
            $table->unsignedBigInteger('member_category_id');
            $table->uuid('category_id'); 
            $table->uuid('transaction_id')->nullable(); 
            $table->date('expired_date');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('member_category_id')
                  ->references('id')
                  ->on('member_categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('transaction_id')
                  ->references('id')
                  ->on('transactions')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

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
        Schema::dropIfExists('member_category_user');
    }
};
