<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\Category;

class BlogController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth')->except(['index']);
        $this->middleware('auth')->except(['index', 'show']);
    }


    //fetching all data from db
    // public function index(){
    //     // $posts = Post::all();
    //     $posts = Post::latest()->get();
    //     return view('blogPosts.blog', compact('posts'));
    // }

    //fetching all data form db + fetching also search result
    public function index(Request $request){
        if($request->search){
            $posts = Post::where('title', 'like', '%' . $request->search . '%')
                    // ->orWhere('body', 'like', '%' . $request->search . '%')->latest()->get();
                    ->orWhere('body', 'like', '%' . $request->search . '%')->latest()->paginate(4);
        } else{
            $posts = Post::latest()->paginate(4);
            // $posts = Post::latest()->get();
        }

        $categories = Category::all();

        return view('blogPosts.blog', compact('posts', 'categories'));
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



    public function edit(Post $post){
        if(auth()->user()->id !== $post->user->id){
            abort(403);
        }
        return view('blogPosts.edit-blog-post', compact('post'));
    }

    public function update(Request $request, Post $post){
        if(auth()->user()->id !== $post->user->id){
            abort(403);
        }
        $request->validate([
            'title' => 'required',
            'image' => 'required | image',
            'body' => 'required'
        ]);

        $title = $request->input('title');
        $postId = $post->id;
        $slug = Str::slug($title, '-') . '-' . $postId;
        $body = $request->input('body');

        //File upload
        $imagePath = 'storage/' . $request->file('image')->store('postsImages', 'public');


        $post->title = $title;
        $post->slug = $slug;
        $post->body = $body;
        $post->imagePath = $imagePath;

        $post->save();

        return redirect()->back()->with('status', 'Post Edited Successfully');
    }




    // public function show($slug){
    //     $post = Post::where('slug', $slug)->first();
    //     return view('blogPosts.single-blog-post', compact('post'));
    // }
    //---------- Using Route model binding -----------
    public function show(Post $post){                       //this $post from route parameter
        return view('blogPosts.single-blog-post', compact('post'));
    }


    public function delete(Post $post){
        $post->delete();
        return redirect()->back()->with('status', 'Post Delete Successfully');
    }
}
