<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->longText('content');
            $table->string('thumbnail_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->longText('schema_markup')->nullable();
            $table->text('tags')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->string('status')->default('ACTIVE');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};
