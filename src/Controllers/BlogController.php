<?php

namespace IFrankSmith\Blogman\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function test()
    {
        $user = auth()->user();

        return response()->json($user->giveBlogPermission('add-post'));
    }
}
