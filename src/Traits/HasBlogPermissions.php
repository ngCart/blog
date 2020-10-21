<?php
namespace App\Traits;

use App\Models\BlogPage;
use App\Models\BlogPermission;
use App\Models\BlogPost;

trait HasBlogPermissions
{
    public function blogPermission()
    {
        return $this->belongsToMany(BlogPermission::class);    
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);    
    }

    public function blogPages()
    {
        return $this->hasMany(BlogPage::class);    
    }

    public function giveBlogPermission($permission)
    {
        $user = $this;
        $permissions = collect($permission)->each(function($permission) use ($user){
            $valid_permission = BlogPermission::whereId($permission)->orWhere('name',$permission)->first();
            if(!$permission)
            {
                abort(400, "Permission [".$permission."] does not exist.");
            }
            $user->blogPermission()->attach($permission);
        });

    }

    public function hasBlogPermission($permission)
    {
        return $this->blogPermissions->where('name', $permission)->count();
    }

    public function blogPermissions()
    {
        return $this->belongsToMany(BlogPermission::class);
    }
}