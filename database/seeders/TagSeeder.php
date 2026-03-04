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
            ['name' => 'Overpowered MC', 'description' => 'Karakter utama sangat kuat sejak awal.'],
            ['name' => 'Reincarnation', 'description' => 'Cerita tentang reinkarnasi ke kehidupan baru.'],
            ['name' => 'Time Travel', 'description' => 'Karakter menjelajah waktu ke masa lalu atau depan.'],
            ['name' => 'Villain', 'description' => 'Fokus pada karakter pria antagonis atau penjahat.'],
            ['name' => 'Villainess', 'description' => 'Fokus pada karakter wanita antagonis atau penjahat.'],
            ['name' => 'Harem', 'description' => 'Karakter utama dikelilingi banyak pasangan potensial.'],
            ['name' => 'Antihero', 'description' => 'Karakter utama dengan moral abu-abu.'],
            ['name' => 'Revenge', 'description' => 'Cerita balas dendam karakter utama.'],
            ['name' => 'System', 'description' => 'Karakter dengan sistem RPG atau game dalam hidupnya.'],
            ['name' => 'Weak to Strong', 'description' => 'Karakter berkembang dari lemah menjadi sangat kuat.'],
            ['name' => 'Cultivation', 'description' => 'Bertema dunia kultivasi khas novel China.'],
            ['name' => 'Ruthless MC', 'description' => 'Karakter utama tanpa belas kasihan pada musuh.'],
            ['name' => 'Love Triangle', 'description' => 'Tiga karakter terlibat dalam hubungan romantis rumit.'],
            ['name' => 'Slice of Life', 'description' => 'Fokus pada kehidupan sehari-hari karakter.'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
