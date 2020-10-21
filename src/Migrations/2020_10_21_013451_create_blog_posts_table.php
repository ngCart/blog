<?php

use \IFrankSmith\Blogman\Models\BlogCategory;
use IFrankSmith\Blogman\Models\BlogPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->text('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('preview_image_url')->nullable();
            $table->text('body');
            $table->string('status')->default('active');
            $table->text('link')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('blog_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable()->default(null);
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('image_url')->nullable();
            $table->string('status')->nullable()->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        $data = ['id'=>1, 'name'=>'Uncategorized', 'slug' => 'uncategorized', 'description' => 'Uncategorized', 'status'=>'active'];
        BlogCategory::insert($data);

        Schema::create('blog_category_blog_post', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('blog_category_id');
            $table->unsignedBigInteger('blog_post_id');
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('cascade');
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blog_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable()->default(null);
            $table->unsignedBigInteger('blog_post_id');
            $table->string('user_name');
            $table->string('user_email');
            $table->text('comment');

            $table->string('status')->nullable()->default('active');

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');
            
        });

        Schema::create('blog_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('body');
            $table->string('status')->nullable()->default('active');
            $table->text('link')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::create('blog_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        $permissions = [
            "add-blog",
            "update-blog",
            "delete-blog",
            "add-page",
            "update-page",
            "delete-page",
        ];

        foreach ($permissions as $permission){
            BlogPermission::create([
                "name"=>$permission
            ]);
        }

        Schema::create('blog_permission_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('blog_permission_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('blog_permission_id')->references('id')->on('blog_permissions')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_permission_user');
        Schema::dropIfExists('blog_permissions');
        Schema::dropIfExists('blog_pages');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_category_blog_post');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('blog_posts');
    }
}
