<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SharedLink extends Model
{
    use HasFactory;

    // Use UUID instead of auto-increment ID
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'shared_id',
        'user_id',
        'post_ids',
        'expires_at',
    ];

    protected $casts = [
        'post_ids' => 'array',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($link) {
            if (empty($link->id)) {
                $link->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
