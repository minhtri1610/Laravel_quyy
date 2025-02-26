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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uid_code')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->date('birth_date')->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable()->comment('Male, Female, Other');
            $table->string('country')->nullable();//tỉnh
            $table->string('city')->nullable();// quận
            $table->string('state')->nullable();//huyện
            $table->string('address')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
