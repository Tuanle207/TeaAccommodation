<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentPhoto extends Model
{
    protected $fillable = [
        'source',
    ];

    public function comment()
    {
        return $this->belongsTo('App\Comment', 'idComment', 'id');
    }
}
