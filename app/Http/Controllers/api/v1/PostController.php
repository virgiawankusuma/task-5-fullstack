<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Article;
use App\Http\Resources\api\v1\PostResource;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // with pagination
    function index()
    {
        // get all posts
        $posts = Article::paginate(5);

        // return posts as a resource
        return new PostResource(true, 'Posts fetched successfully', $posts);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // store image
        $image = $request->file('image');
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/images', $image_name);

        // create post
        $post = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => Category::whereBelongsTo(auth()->user())->pluck('id')->first(),
            'image' => $image_name,
            'user_id' => auth()->user_id
        ]);

        return new PostResource(true, 'Post created successfully', $post);
    }

    function show($id)
    {
        $post = Article::find($id);

        if ($post) {
            return new PostResource(true, 'Post fetched successfully', $post);
        }

        return new PostResource(false, 'Post not found', null);
    }

    function update(Request $request, $id)
    {
        $post = Article::find($id);

        if ($post) {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            //check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            //check if image is not empty
            if ($request->hasFile('image')) {
                // store image
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images', $image_name);

                // delete old image
                $old_image = $post->image;
                if ($old_image) {
                    unlink(storage_path('app/public/images/' . $old_image));
                }

                // update post

                $post->update([
                    'title' => $request->title,
                    'content' => $request->content,
                    'category_id' => $request->category_id,
                    'image' => $image_name,
                ]);

                return new PostResource(true, 'Post updated successfully', $post);

            } else {
                // update post
                $post->update([
                    'title' => $request->title,
                    'content' => $request->content,
                    'category_id' => $request->category_id,
                ]);

                return new PostResource(true, 'Post updated successfully', $post);
            }
        }

        return new PostResource(false, 'Post not found', null);
    }

    function destroy($id)
    {
        $post = Article::find($id);

        if ($post) {
            $post->delete();

            return new PostResource(true, 'Post deleted successfully', null);
        }

        return new PostResource(false, 'Post not found', null);
    }
}
