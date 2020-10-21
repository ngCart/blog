<?php
namespace IFrankSmith\Blogman\Traits;

use IFrankSmith\Blogman\Models\BlogPage;
use IFrankSmith\Blogman\Models\BlogPermission;
use IFrankSmith\Blogman\Models\BlogPost;

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
            if(!$valid_permission)
            {
                abort(400, "Permission [".$permission."] does not exist.");
            }
            $user->blogPermissions()->attach($valid_permission);
        });

    }

    public function giveAllBlogPermissions()
    {
        $user = $this;
        $permissions = BlogPermission::all()->each(function($permission) use ($user){
            $user->giveBlogPermission($permission->name);
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