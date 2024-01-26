<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'color'];

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class);
    }

    public function nombre(): Attribute
    {
        //Le pongo el # delante y lo pongo todo en minúscula
        return Attribute::make(
            get: fn ($v) => "#" . $v,
            set: fn ($v) => strtolower($v)
        );
    }
}
