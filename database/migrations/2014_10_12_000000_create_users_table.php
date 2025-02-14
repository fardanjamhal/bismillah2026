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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('referrer_id')->nullable();
            $table->timestamps();
            $table->string('code')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('member_type')->default(2); // 1 member internal, 2 member external
            $table->tinyInteger('level'); // 1 admin, 2 student
            $table->tinyInteger('is_active')->default(0);
            $table->double('account_balance')->default(0);
            $table->json('membership_type')->nullable();
            $table->date('expiry_verification_date')->nullable();
            // referrer
            $table->integer('is_referrer')->default(0);
            $table->tinyInteger('commission_type')->nullable(); // 1 percentage, 2 nominal
            $table->double('commission')->nullable();
            $table->rememberToken();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('referrer_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_referrer_id_foreign');
        });

        Schema::dropIfExists('users');
    }
};
