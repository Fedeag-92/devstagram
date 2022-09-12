<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [
            'username' => 'required|unique:users,username,' . auth()->user()->id . '|min:3|max:20|not_in:editar-perfil'
        ]);

        if($request->imagen){
            //Imagen en memoria
            $imagen = $request->file('imagen');

            //Nombre de la imagen, para que sea unico en el servidor
            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            //crea una instancia de intervention image, con el metodo make
            $imagenServidor = Image::make($imagen);
            //Se agrega el efecto, el tamaño de todas las imagenes va a ser de 1000 x 1000
            $imagenServidor->fit(1000, 1000);

            //Ruta de la imagen
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;

            //Se guarda la imagen en el servidor en la ruta definida antes
            $imagenServidor->save($imagenPath);
        }

        $usuario = User::find(auth()->user()->id);

        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;

        $usuario->save();

        return redirect()->route('posts.index', $usuario->username);
    }
}
