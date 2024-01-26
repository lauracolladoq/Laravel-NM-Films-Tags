<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Terror' => "#FFCFEA",
            'Romance' => "#FEFFBE",
            'Comedia' => "#CBFFE6",
            'Acción' => "#AFE9FF",
            'Documental' => "#BFB9FF"
        ];

        foreach ($tags as $n => $c) {
            Tag::create([
                'nombre' => $n,
                'color' => $c
            ]);
        }
    }
}
