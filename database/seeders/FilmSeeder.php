<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Guardo todas las películas creadas y le asigno los tags
        $films = Film::factory(50)->create();

        foreach ($films as $item) {
            $item->tags()->attach($this->devolverIdTags());
        }
    }

    //Devuelve array con todas las ids
    private function devolverIdTags(): array
    {
        $tags = [];
        //pluck sirve para recuperar un array de valores de una única columna de la bd
        $arrayTags = Tag::pluck('id')->toArray();
        $indices = array_rand($arrayTags, random_int(2, count($arrayTags)));
        foreach ($indices as $indice) {
            $tags[] = $arrayTags[$indice];
        }
        return $tags;
    }
}
