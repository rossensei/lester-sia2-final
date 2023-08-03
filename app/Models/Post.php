<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'privacy',
        'photo',
        'user_id',
    ];

    protected $appends = [
        'photo_url'
    ];

    public function getPhotoUrlAttribute() 
    {
        $url = $this->photo ? asset("images/photos/" . $this->photo) : null;
        return $url;
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
