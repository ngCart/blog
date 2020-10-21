<?php

namespace IFrankSmith\Blogman\Controllers;
// namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // Fetch all Posts
        $posts = BlogPost::latest()->get();
        return response()->json($posts);
    }

    public function show(BlogPost $post)
    {
        // Feetch Post with comments
        $comments = BlogComment::whereBlogPostId($post->id)->whereNull('parent_id')->with('replies')->latest()->paginate(10);
        return response()->json(['post'=>$post, 'comments'=>$comments]);
    }

    public function store(Request $request)
    {
        $user =  auth()->user();
        return $user->blogPermissions;
        $request->validate([
            "title" => "required|string|max:1000",
            "body"=>"required",
            "slug"=>"required|sometimes"
        ]);


        // Create post
        $post = $user->blogPosts()->create($request->only(['title', 'slug', 'description', 'status', 'body', 'link', 'preview_image_url']));

        return response()->json(['message'=>"Post created successfully",'data'=>$post], 201);
    }

    public function update(Request $request, BlogPost $post)
    {
        // Update post
        $request->validate([
            "title" => "string",
            "description"=>"string",
            "slug"=>"required|sometimes"
        ]);
        $post->update($request->only(['title', 'slug', 'description', 'status', 'body', 'link', 'preview_image_url']));

        return response()->json(['message'=>'Post updated successfully', 'data'=> $post]);
    }

    public function destroy(BlogPost $post)
    {
        // Delete post
        try{
            $post->delete();
        }
        catch(\Throwable $e){
            return response()->json(["message"=>"Delete operation failed", "error"=>$e], 400);
        }

        return response()->json(['message'=> "Deleted successfully"]);
    }
    
    public function comment(BlogPost $post, Request $request)
    {
        // Validate
        $request->validate([
            'comment' =>'required',
            'comment_id' => 'required|sometimes|exists:blog_comments,id',
            'user_name' => 'required|string',
            'user_email' => 'required|email'
        ]);
        
        // Save as comment or as reply
        if($request->has('comment_id'))
        {
            $parent_comment = BlogComment::whereBlogPostId($post->id)->whereId($request->comment_id)->first();
            
            if(!$parent_comment){
                return response()->json(["error"=>"The comment cannot be made on this post."]);
            }
        }
        
        $comment = $post->comments()->create([
            'parent_id' => $request->comment_id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'comment' => nl2br(strip_tags($request->comment))
            ]);
        
            return response()->json(["message"=>"Comment created successfully.", "data"=>$comment]);
    }

    public function getCommentReplies(Request $request)
    {
        $request->validate([
                'comment_id' => 'required|numeric|exists:blog_comments,id',
            ]);

            // Fetch the comment and its replies
            $parent_comment = Comment::find($request->comment_id);
            $replies = Comment::getReplies($request->message_id)->latest()->paginate(10);

            return response()->json(['parent_comment'=>$parent_comment,'replies'=>$replies]);
    }
}
