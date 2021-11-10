<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Post;

class BlogController extends Controller
{
    public function index(){
        // $posts = Post::all();
        $posts = Post::latest()->get();
        return view('blogPosts.blog', compact('posts'));
    }



    public function create(){
        return view('blogPosts.create-blog-post');
    }



    public function store(Request $request){
        // $allInputs = $request->all();   //sokol input niye nibe
        // dd($allInputs);

        // $title = $request->input('title');   //sokol title input ta niye nibe
        // dd($title);

        // $request->validate([
        //     'title' => 'required'
        // ]);
        // dd('Validation passed. You can now request the input');

        $request->validate([
            'title' => 'required',
            'image' => 'required | image',
            'body' => 'required'
        ]);

       $title = $request->input('title');
       //$slug = Str::slug($title, '-');
       $postId = Post::latest()->take(1)->first()->id + 1;
       $slug = Str::slug($title, '-') . '-' . $postId;
       $user_id = Auth::user()->id;
       $body = $request->input('body');

       //File upload
       $imagePath = 'storage/'.$request->file('image')->store('postsImages', 'public'); //php artisan storage:link

       $post = new Post();
       $post->title = $title;
       $post->slug = $slug;
       $post->user_id = $user_id;
       $post->body = $body;
       $post->imagePath = $imagePath;

       $post->save();

       return redirect()->back()->with('status', 'Post Created Successfully');
    }




    // public function show($slug){
    //     $post = Post::where('slug', $slug)->first();
    //     return view('blogPosts.single-blog-post', compact('post'));
    // }
    //---------- Using Route model binding -----------
    public function show(Post $post){                       //this $post from route parameter
        return view('blogPosts.single-blog-post', compact('post'));
    }
}
