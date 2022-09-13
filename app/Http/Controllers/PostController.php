<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }

    public function index(User $user)
    {
        $posts = Post::where('user_id', $user->id)->latest()->paginate(8);

        return view('dashboard', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return view ('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);

        Post::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id
        ]);

        //Otra forma de crear
        // $post = new Post;
        // $post->titulo = $request->titulo;
        // $post->descripcion = $request->descripcion;
        // $post->imagen = $request->imagen;
        // $post->titulo = auth()->user()->id;
        // $post->save();

        //Tercer forma, guardando con la relacion
        // $request->user()->posts()->create([
        //     'titulo' => $request->titulo,
        //     'descripcion' => $request->descripcion,
        //     'imagen' => $request->imagen,
        //     'user_id' => auth()->user()->id
        // ]);

        return redirect()->route('posts.index', auth()->user());
    }

    public function show(User $user, Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'user' => $user
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return redirect()->route('posts.index', auth()->user()->username);
    }
}
