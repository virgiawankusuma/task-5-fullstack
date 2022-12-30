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
        // articles with pagination
        // dd(Article::all());
        $posts = Article::paginate(6);
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);

        // if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }


        // upload image handler
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/images', $imageName);

        // create post
        $post = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image' => $imageName,
            'user_id' => Auth::user()->id,
        ]);

        // return redirect to home
        return redirect()->route('home');
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        // if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        //check if image is not empty
        if ($request->hasFile('image')) {
            // upload image handler
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);

            // delete old image
            Storage::delete('public/images/' . $post->image);

            // update post
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'category_id' => $request->category_id,
                'image' => $imageName,
                'user_id' => Auth::user()->id,
            ]);
        } else {
            // update post
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'category_id' => $request->category_id,
                'user_id' => Auth::user()->id,
            ]);
        }

        // return redirect to home
        return redirect()->route('home');
    }
}
