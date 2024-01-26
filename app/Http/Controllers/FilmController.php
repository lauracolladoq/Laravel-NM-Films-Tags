<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $films = Film::orderBy('titulo')->paginate(8);
        return view('films.index', compact('films'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Cargo los tags que puedo asignarle
        $tags = Tag::select('id', 'nombre')->orderBy('id')->get();
        return view('films.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'min:3', 'unique:films,titulo'],
            'descripcion' => ['required', 'string', 'min:10'],
            'imagen' => ['nullable', 'image', 'max:2048'],
            'tags' => ['required', 'array', 'min:1', 'exists:tags,id']
        ]);

        //Una vez pasadas las validaciones guardo la película
        $film = Film::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => ($request->imagen) ? $request->imagen->store('films') : "films/default.jpeg",
        ]);

        //Le asignamos a la peli creada los tags, ten en cuenta que desde el create le indicamos que guarde los tags en un array
        $film->tags()->attach($request->tags);

        return redirect()->route('films.index')->with('mensaje', 'Film guardada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Film $film)
    {
        //dd($film->tags);
        return view('films.show', compact('film'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Film $film)
    {
        //Le paso los tags de ese film
        $tagsFilm = $film->getFilmTagsId();

        //Le paso los tags disponibles
        $tags = Tag::select('id', 'nombre')->orderBy('nombre')->get();

        return view('films.edit', compact('film', 'tags', 'tagsFilm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Film $film)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'min:3', 'unique:films,titulo,' . $film->id],
            'descripcion' => ['required', 'string', 'min:10'],
            'imagen' => ['nullable', 'image', 'max:2048'],
            'tags' => ['required', 'array', 'min:1', 'exists:tags,id']
        ]);

        $ruta = $film->imagen;
        if ($request->imagen) {
            if (basename($film->imagen) != 'default.jpeg') {
                Storage::delete($film->imagen);
            }
            $ruta = $request->imagen->store('films');
        }
        $film->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $ruta,
        ]);

        //Actualizamos sus etiquetas, esto le quita a la film todas las etiquetas y le pone las nuevas
        $film->tags()->sync($request->tags);

        return redirect()->route('films.index')->with('mensaje', 'Film editada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Film $film)
    {
        if (basename($film->imagen) != 'default.jpeg') {
            Storage::delete($film->imagen);
        }

        $film->delete();
        return redirect()->route('films.index')->with('mensaje', 'Film borrada correctamente');
    }
}
