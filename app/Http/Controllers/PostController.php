<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image as Image;

use App\Models\Post as Post;

use App\Models\User as User;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //prevent user from reaching this page without auth
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user)
    {
        $users = auth()->user()->following()->pluck('profiles.user_id');

        $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);

        $userFollowing = count(auth()->user()->following);

        return view('posts.index', compact('posts', 'users', 'userFollowing'));
    }

    public function discover()
    {
        $users = User::all()->except(auth()->user()->id)->pluck('id');

        $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);

        return view('posts.discover', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {
        //validate data before creating
        $data = request()->validate([
            'title' => 'required',
            'caption' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'image' => 'required|image',
        ]);

        $image = Image::make(request('image'))->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });


        $storagePath = Storage::disk('s3')->put(
            'uploads/' . $image,
            'public'
        );

        //create data by auth user
        auth()->user()->posts()->create([
            'title' => $data['title'],
            'caption' => $data['caption'],
            'ingredients' => $data['ingredients'],
            'instructions' => $data['instructions'],
            'image' => $imagePath,
        ]);

        return redirect('/profile/' . auth()->user()->id);
    }

    public function show(\App\Models\Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(\App\models\Post $post, User $user)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(\App\Models\Post $post, User $user)
    {
        $this->authorize('update', $post);
        $data = request()->validate([
            'title' => 'required',
            'caption' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
        ]);

        Post::find($post->id)->update($data);

        return redirect("/p/{$post->id}");
    }

    public function destroy(\App\Models\Post $post, User $user)
    {

        $post = Post::findOrFail($post->id);
        $post->delete();

        return redirect("/profile/{$post->user_id}");
    }
}
