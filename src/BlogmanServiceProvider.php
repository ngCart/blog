<?php

namespace IFrankSmith\Blogman;

use Illuminate\Support\ServiceProvider;

class BlogmanServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');

        $this->publishes([
            __DIR__.'/Migrations/2020_10_21_013451_create_blog_posts_table.php' => database_path('migrations')
        ], 'migrations');

        $this->loadMigrationsFrom(__DIR__.'/Migrations/2020_10_21_013451_create_blog_posts_table.php');
    }

    public function register()
    {

    }
}