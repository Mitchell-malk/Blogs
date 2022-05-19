<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'article_id'];

    // 关联用户
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
