<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubComment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'comment_id' ,'body'];

    public function subcomment()
    {
        return $this->belongsTo(Comment::class);
    }
}
