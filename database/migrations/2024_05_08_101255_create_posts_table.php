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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->references('uuid')->on('users')->onDelete('cascade');
            $table->string('cover_image');
            $table->string('title');
            $table->string('category');
            $table->string('organizedBy');
            $table->string('start_date')->default('');
            $table->string('end_date')->default('');
            $table->string('all_day')->default('');
            $table->string('start_time')->default('');
            $table->string('end_time')->default('');
            $table->string('availability')->default('');
            $table->string('repeat')->default('');
            $table->string('audience_limit')->default('');
            $table->string('event_price')->default('');
            $table->string('password')->default('');
            $table->string('city')->default('');
            $table->string('address')->default('');
            $table->string('website')->default('');
            $table->string('registration_link')->default('');
            $table->string('zoom_link')->default('');
            $table->string('upload_images')->default('');
            $table->string('attachments')->default('');
            $table->string('description')->default('');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
