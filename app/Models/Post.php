<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'platform',
        'post_url',
        'shared',
        'uuid',
    ];

    // Relation to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
