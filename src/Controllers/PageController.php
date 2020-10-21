<?php

namespace IFrankSmith\Blogman\Controllers;
// namespace App\Http\Controllers;

use App\Models\BlogPage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = BlogPage::all();
        return response()->json(['data'=>$pages]);
        return Page::all();
    }

    public function show(BlogPage $page)
    {
        return response()->json(['data'=>$page]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            "name"=> "required|unique:blog_pages,name",
            "title" => "string",
            "body" => "required",
            "status" => "srting",
            "link" => "string"
        ]);
        // Create post
        $data = $request->only(['name', 'title', 'description', 'status', 'body', 'link']);


        $page = $user->blogPages()->create($data);

        return response()->json(['message'=>"Page created successfully",'data'=>$page], 201);
    }

    public function update(Request $request, BlogPage $page)
    {
        $request->validate([
            "name"=> "required|unique:blog_pages,name,".request()->route('page')->id,
            "title" => "string",
            "body" => "required",
            "status" => "srting",
            "link" => "string"
        ]);
        // Update page
        $page->update($request->only(['name', 'title', 'description', 'status', 'body', 'link']));
        return response()->json(['message'=>'Page updated successfully', 'data'=> $page]);
    }

    public function destroy(BlogPage $page)
    {
        // Delete post
        try{
            $page->delete();
        }
        catch(\Throwable $e){
            return response()->json(["message"=>"Delete operation failed", "error"=>$e], 400);
        }

        return response()->json(['message'=> "Deleted successfully"]);
    }
}
