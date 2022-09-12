<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LikePost extends Component
{
    public $post;
    public $isLiked;
    public $likes;

    public function mount($post)
    {
        $this->isLiked = $post->checkLike( auth()->user() );
        $this->likes = $post->likes()->count();
    }

    public function like()
    {
        if($this->post->checkLike( auth()->user() )){
            //Del usuario que hace el request, obtener los likes
            //(porque se definio la relacion eloquent en el modelo)
            //filtrar por el post al que le dio like, esa tupla se borra.
            $this->post->likes()->where('post_id', $this->post->id)->delete();

            //De esta manera se va a hacer el re-render, ya que cambia la variable isLiked
            $this->isLiked = false;
            $this->likes--;
        }else{
            $this->post->likes()->create([
                'user_id' => auth()->user()->id
            ]);
            $this->isLiked = true;
            $this->likes++;
        }
    }

    public function render()
    {
        return view('livewire.like-post');
    }
}
