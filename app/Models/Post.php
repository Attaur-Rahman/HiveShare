<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Base model class
use Illuminate\Support\Str; // For generating UUIDs

class Post extends Model
{
    // Set primary key name
    protected $primaryKey = 'post_id'; // Use post_id as primary key

    // Disable auto-increment IDs
    public $incrementing = false; // UUIDs are generated manually

    // Primary key type
    protected $keyType = 'string'; // Primary key is a string

    // Disable Laravel timestamps (no updated_at)
    public $timestamps = false; // Only created_at is used

    // Fields allowed for mass assignment
    protected $fillable = [
        'post_id', // Unique post ID
        'user_id', // ID of user who created the post
        'platform', // Platform name (Instagram, Twitter, etc.)
        'title', // Title of the post
        'description', // Description of the post
        'url', // URL
        'is_favourite', // Whether post is marked as favourite
        'created_at', // Post creation time
    ];

    // Cast fields to specific data types
    protected $casts = [
        'created_at' => 'datetime', // Convert created_at to datetime
        'is_favourite' => 'boolean',
    ];

    // Auto-generate UUID when creating a post
    protected static function boot()
    {
        parent::boot(); // Call parent boot method

        static::creating(function ($post) { // Before creating record
            if (empty($post->post_id)) { // If no ID set
                $post->post_id = (string) Str::uuid(); // Generate UUID
            }
        });
    }

    // Relation: Post belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // Link post to user
    }
}
