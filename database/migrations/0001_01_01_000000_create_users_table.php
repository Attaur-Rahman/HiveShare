<?php

// Import migration and schema classes
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Return an anonymous migration class
return new class extends Migration
{
    // Run the migrations
    public function up(): void
    {
        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id')->primary(); // Primary key for user
            $table->string('name'); // User name
            $table->string('email')->unique(); // Unique email
            $table->string('password'); // User password
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Creation time
        });

        // Create posts table
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('post_id')->primary(); // Primary key for post
            $table->string('user_id'); // ID of user who made the post
            $table->foreign('user_id') // Foreign key setup
                ->references('user_id')->on('users') // Link to users table
                ->onDelete('cascade')->onUpdate('cascade'); // Cascade on delete or update
            $table->string('platform'); // Post platform
            $table->string('title'); // Post title
            $table->text('description')->nullable(); // Post description (optional)
            $table->string('url', 2083); // link
            $table->boolean('is_favourite')->default(false); // Mark as favourite
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Creation time

        });

        // Create shared_links table
        Schema::create('shared_links', function (Blueprint $table) {
            $table->uuid('shared_id')->primary(); // Primary key for shared link
            $table->string('user_id'); // ID of user who shared
            $table->foreign('user_id') // Foreign key setup
                ->references('user_id')->on('users') // Link to users table
                ->onDelete('cascade')->onUpdate('cascade'); // Cascade on delete or update
            $table->timestamp('expires_at')->nullable(); // Link expiry time
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Creation time
        });
    }

    // Reverse the migrations
    public function down(): void
    {
        Schema::dropIfExists('users'); // Delete users table
        Schema::dropIfExists('posts'); // Delete posts table
        Schema::dropIfExists('shared_links'); // Delete shared_links table
    }
};
