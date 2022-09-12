<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        //Imagen en memoria
        $imagen = $request->file('file');

        //Nombre de la imagen, para que sea unico en el servidor
        $nombreImagen = Str::uuid() . "." . $imagen->extension();

        //crea una instancia de intervention image, con el metodo make
        $imagenServidor = Image::make($imagen);
        //Se agrega el efecto, el tamaño de todas las imagenes va a ser de 1000 x 1000
        $imagenServidor->fit(1000, 1000);

        //Ruta de la imagen
        $imagenPath = public_path('uploads') . '/' . $nombreImagen;

        //Se guarda la imagen en el servidor en la ruta definida antes
        $imagenServidor->save($imagenPath);

        return response()->json(['imagen' => $nombreImagen]);
    }
}
