<?php

namespace IFrankSmith\Blogman\Models;
// namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $guarded = [
    ];

    protected static function booted()
    {
        static::creating(function ($post) {
            $post->slug = \Str::slug($post->title);
        });
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }
}
