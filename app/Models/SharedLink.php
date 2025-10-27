<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Base model class
use Illuminate\Support\Str; // For generating UUIDs

class SharedLink extends Model
{
    // Set primary key name
    protected $primaryKey = 'shared_id';

    // Use string as primary key type
    protected $keyType = 'string'; // Primary key is a string

    // Disable auto-increment IDs
    public $incrementing = false; // UUIDs are manually generated

    // Disable default timestamps (no updated_at)
    public $timestamps = false; // Only created_at is used

    // Fields allowed for mass assignment
    protected $fillable = [
        'shared_id', // Unique ID of the shared link
        'user_id', // ID of the user who shared
        'expires_at', // When the link expires
        'created_at', // When the link was created
    ];

    // Automatically cast these fields to datetime
    protected $casts = [
        'expires_at' => 'datetime', // Convert expires_at to datetime
        'created_at' => 'datetime', // Convert created_at to datetime
    ];

    // Automatically generate UUID when creating a record
    protected static function boot()
    {
        parent::boot(); // Call parent boot method

        static::creating(function ($link) { // Before creating record
            if (empty($link->shared_id)) { // If shared_id not set
                $link->shared_id = (string) Str::uuid(); // Generate UUID
            }
        });
    }

    // Define relation to User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // Each shared link belongs to a user
    }
}
