<?php

namespace Database\Seeders;

use App\Models\Genre;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Fantasy', 'description' => 'Cerita dengan elemen magis, dunia lain, atau makhluk supernatural.'],
            ['name' => 'Romance', 'description' => 'Fokus pada hubungan percintaan antar karakter.'],
            ['name' => 'Action', 'description' => 'Dipenuhi adegan pertarungan, kejar-kejaran, atau konflik fisik.'],
            ['name' => 'Adventure', 'description' => 'Cerita perjalanan dan penjelajahan dunia.'],
            ['name' => 'Comedy', 'description' => 'Cerita dengan unsur humor yang menghibur.'],
            ['name' => 'Drama', 'description' => 'Menggambarkan konflik emosional antar karakter.'],
            ['name' => 'Mystery', 'description' => 'Fokus pada pemecahan misteri atau kasus.'],
            ['name' => 'Horror', 'description' => 'Cerita menyeramkan dengan suasana menegangkan.'],
            ['name' => 'Sci-Fi', 'description' => 'Bertema teknologi futuristik, luar angkasa, atau sains.'],
            ['name' => 'Slice of Life', 'description' => 'Cerita kehidupan sehari-hari yang realistis.'],
            ['name' => 'Isekai', 'description' => 'Karakter utama pindah ke dunia lain.'],
            ['name' => 'Sports', 'description' => 'Fokus pada olahraga dan kompetisi.'],
            ['name' => 'Historical', 'description' => 'Bertema sejarah atau berlatar belakang masa lalu.'],
            ['name' => 'Supernatural', 'description' => 'Mengandung elemen gaib atau kekuatan supranatural.'],
            ['name' => 'Mecha', 'description' => 'Bertema robot raksasa atau mesin tempur.'],
            ['name' => 'Music', 'description' => 'Fokus pada musik, pertunjukan, atau industri musik.'],
            ['name' => 'Psychological', 'description' => 'Menggali aspek psikologis karakter dan konflik mental.'],
            ['name' => 'Thriller', 'description' => 'Cerita menegangkan dengan plot yang penuh ketegangan.'],
            ['name' => 'Ecchi', 'description' => 'Mengandung unsur sensual atau erotis, namun tidak eksplisit.'],
            ['name' => 'Yaoi', 'description' => 'Fokus pada hubungan romantis antara karakter pria.'],
            ['name' => 'Yuri', 'description' => 'Fokus pada hubungan romantis antara karakter wanita.'],
        ];

        foreach ($genres as $genre) {
            Genre::updateOrCreate(['name' => $genre['name'], 'description' => $genre['description']], $genre);
        }
    }
}
