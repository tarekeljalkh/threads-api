<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'thread_id',
        'body'
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function subcomments()
    {
        return $this->hasMany(SubComment::class);
    }
}
