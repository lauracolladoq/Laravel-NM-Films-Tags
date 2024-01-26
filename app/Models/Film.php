<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Film extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descripcion', 'imagen'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    //Accessors y muttators

    public function titulo(): Attribute
    {
        return Attribute::make(
            set: fn ($v) => ucfirst($v)
        );
    }

    public function descripcion(): Attribute
    {
        return Attribute::make(
            set: fn ($v) => ucfirst($v)
        );
    }

    //Para devolver todos los ids de los tags de una film en un array
    public function getFilmTagsId(): array
    {
        $tagsFilm = [];
        //Me traigo todos los tags de esa film y los guardo en un array para poder mostrarlos
        foreach ($this->tags as $item) {
            $tagsFilm[] = $item->id;
        }
        return $tagsFilm;
    }
}
