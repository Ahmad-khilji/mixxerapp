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
            $table->uuid()->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('timezone')->nullable();
            $table->string('platform')->nullable();
            $table->string('platform_id')->nullable();

            $table->string('profile_image')->nullable();
            $table->string('location')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('age')->nullable();
            $table->string('bio')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('language')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('occupation')->nullable();
            $table->string('education')->nullable();
           
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
