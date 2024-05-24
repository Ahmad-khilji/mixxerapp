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
            $table->string('first_name')->default('');
            $table->string('last_name')->default('');
            $table->string('email')->unique();
            $table->string('password')->default('');
            $table->string('timezone')->default('');
            $table->string('platform')->default('');
            $table->string('platform_id')->default('');

            $table->string('profile_image')->default('');
            $table->string('location')->default('');
            $table->string('dob')->default('');
            $table->string('gender')->default('');
            $table->string('religion')->default('');
            $table->string('age')->default('');
            $table->string('bio')->default('');
            $table->string('lat')->default('');
            $table->string('lng')->default('');
            $table->string('language')->default('');
            $table->string('ethnicity')->default('');
            $table->string('occupation')->default('');
            $table->string('education')->default('');
            $table->text('interest')->default('');
           
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
