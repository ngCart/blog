<?php

namespace IFrankSmith\Blogman\Models;
// namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(BlogPost::class);
    }

    public static function getReplies($parent_id)
    {
       return BlogComment::where('parent_id', $parent_id);
    }

    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id', 'id');
    }
}
