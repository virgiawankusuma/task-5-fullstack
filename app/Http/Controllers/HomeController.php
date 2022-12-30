<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function index()
    {
        // dd(Article::all());
        $posts = Article::paginate(5);
        return view('home', [
            'posts' => $posts,
            'categories' => Category::all(),
        ]);
    }

    function create()
    {
        return view('blog.add', [
            'id' => Auth::user()->id,
            'categories' => Category::all(),
        ]);
    }

    function store(Request $request)
    {
        // validation
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        // if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // create post
        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image' => $request->image,
            'status' => $request->status,
            'user_id' => Auth::user()->id,
        ]);

        // return redirect to home
        return redirect()->route('home')->with('success', 'Post created successfully');
    }

    function edit($id)
    {
        return view('blog.edit', [
            'post' => Article::find($id),
            'categories' => Category::all(),
        ]);
    }

    function update(Request $request, $id)
    {
        $post = Article::find($id);

        // validation
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        // if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // update post
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image' => $request->image,
            'status' => $request->status,
            'user_id' => Auth::user()->id,
        ]);

        // return redirect to home
        return redirect()->route('home')->with('success', 'Post updated successfully');
    }

    function destroy($id)
    {
        $post = Article::find($id);
        $post->delete();

        // return redirect to home
        return redirect()->route('home')->with('success', 'Post deleted successfully');
    }
}
