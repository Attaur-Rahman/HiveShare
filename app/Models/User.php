<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Base user class
use Laravel\Sanctum\HasApiTokens; // For API tokens
use Tymon\JWTAuth\Contracts\JWTSubject; // For JWT authentication

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens; // Enable API token usage

    // Primary key name
    protected $primaryKey = 'user_id'; // Use 'user_id' as primary key

    // Disable auto-increment
    public $incrementing = false; // IDs are not auto-incremented

    // Primary key type
    protected $keyType = 'string'; // Primary key is a string

    // Disable Laravel timestamps (no updated_at)
    public $timestamps = false; // We handle created_at manually

    // Fillable fields for mass assignment
    protected $fillable = [
        'user_id', // User ID
        'name', // User name
        'email', // User email
        'password', // User password
        'created_at', // Created timestamp
    ];

    // Automatically cast these fields to datetime
    protected $casts = [
        'created_at' => 'datetime', // Convert created_at to datetime
    ];

    // Hidden fields in API responses
    protected $hidden = [
        'password', // Hide password
    ];

    // Define attribute casting
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Automatically hash password
        ];
    }

    // Return JWT identifier
    public function getJWTIdentifier()
    {
        return $this->user_id; // Use user_id for JWT
    }

    // Add custom JWT claims
    public function getJWTCustomClaims()
    {
        return []; // No extra claims
    }

    // Relation: User has many posts
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'user_id'); // Link to posts table
    }
}
