<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;

// IfYou Want SQL Queryes Instead of Eloquent
use DB; 

class PostsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show'] ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    

        // If You Want To Use SQL Query
        // $posts = DB::select("SELECT * FROM posts");

        // Fetch All Data
        // $posts = Post::all();  


        // Fetch By Order REMEMBER MUST ADD GET at the END .. this to get the recent at the very top
        // $posts = Post::orderBy('id', 'desc')->take(1)->get();  
        // $posts = Post::orderBy('id', 'desc')->get();  

        // Pagination
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);  
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate( $request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999' 
        ]);

        // Handle File Upload

        if ($request->hasFile('cover_image')) {
            // Get Filename With Ext
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get Filename Only
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get Extension Only
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename To Store
            $fileNameToStore = $filename ."_". time().".".$extension; 
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        // Create Post

        $post = new Post;

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;

        $post->save();

        return redirect('/posts')->with('success', 'Post Created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        // Check Correct User To Avoid editing by url and redirect them

        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate( $request, [
            'title' => 'required',
            'body' => 'required' 
        ]);

        if ($request->hasFile('cover_image')) {
            // Get Filename With Ext
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get Filename Only
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get Extension Only
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename To Store
            $fileNameToStore = $filename ."_". time().".".$extension; 
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        }

        // Create Post

        $post = Post::find($id);

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        
        if ($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }

        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }
        
        if ($post->cover_image != 'noimage.jpg') {
            //Delete Image
            $a = Storage::delete('public/cover_images/'. $post->cover_image);
        }

        $post->delete();
        return redirect('/posts')->with('success', 'Post Removed');

    }
}
