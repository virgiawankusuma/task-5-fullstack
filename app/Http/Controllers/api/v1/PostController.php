<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Post;
use App\Http\Resources\api\v1\PostResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // with pagination
    function index()
    {
        $posts = Post::paginate();

        return new PostResource($posts);
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'required'
        ]);

        $post = Post::create($validated);

        return new PostResource($post);
    }

    function show(Post $post)
    {
        return new PostResource($post);
    }

    function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'required'
        ]);

        $post->update($validated);

        return response([
            'message' => 'Post updated successfully',
            'post' => new PostResource($post)
        ]);
    }

    function destroy(Post $post)
    {
        $post->delete();

        return response([
            'message' => 'Post deleted successfully'
        ]);
    }
}
