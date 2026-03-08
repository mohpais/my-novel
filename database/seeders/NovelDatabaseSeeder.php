<?php

namespace Database\Seeders;

use App\Models\Novel;
use App\Models\Genre;
use App\Models\Tag;
use App\Models\Character;
use App\Models\CharacterPower;
use Illuminate\Database\Seeder;

class NovelDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Genre & Tags
        $genre = Genre::firstOrCreate(['name' => 'Fantasy', 'slug' => 'fantasy']);
        $tagMagic = Tag::firstOrCreate(['name' => 'Magic System', 'slug' => 'magic-system']);
        $tagKingdom = Tag::firstOrCreate(['name' => 'Kingdom Building', 'slug' => 'kingdom-building']);

        // 2. Buat Novel Utama
        $novel = Novel::create([
            'title' => 'Throne of Fractured Fates',
            'slug' => 'throne-of-fractured-fates',
            'synopsis' => 'Setelah memenangkan perang yang mustahil melawan pasukan langit, Kaelen justru terjebak ke dalam segel abadi oleh dewi yang ia kalahkan. Namun, seorang entitas misterius tiba-tiba membebaskannya dan melemparnya ke sebuah dunia asing dengan langit yang belum pernah ia lihat sebelumnya.

Kaelen tidak tahu siapa yang menyelamatkannya atau apa tujuannya. Ia hanya punya satu keinginan sederhana: ingin hidup tenang tanpa harus menjadi alat siapa pun lagi.

Sayangnya, ketenangan itu tidak bertahan lama. Kaelen mendarat di Kerajaan Lurn, sebuah negeri yang sedang hancur karena perang saudara dan perebutan takhta. Dengan kekuatannya tersegel rapat, Kaelen perlahan terseret kembali ke dalam kekacauan yang sangat ia benci.

Di dunia baru ini, tidak ada yang tahu siapa Kaelen sebenarnya. Tidak ada yang tahu bahwa pria ini adalah "Sang Malapetaka" yang pernah memusnahkan satu peradaban.

Akankah Kaelen berhasil mendapatkan kebebasan yang ia cari? Atau apakah ia akan kembali menjadi "Sang Malapetaka" yang sama? Atau apakah ia akan berubah menjadi "Sang Juru Selamat" di dunia baru yang sedang dilanda oleh kehancuran ini?

Throne of Fractured Fates: Kisah tentang kebencian, kekuasaan, dan takdir yang dibengkokkan oleh Sang Malapetaka itu sendiri.',
            'status' => 'Ongoing'
        ]);

        $novel->genres()->attach($genre->id);
        $novel->tags()->attach([$tagMagic->id, $tagKingdom->id]);

        // // 3. World Building (Lore)
        // $loreData = [
        //     [
        //         'title' => 'Infinite Prison (Segel Dimensi)',
        //         'category' => 'Location',
        //         'content' => 'Sebuah penjara dimensi tanpa ujung tempat Kaelen dikurung selama ribuan tahun oleh Sang Dewi. Segel ini akhirnya runtuh karena anomali sejarah yang menulis ulang realitas.',
        //     ],
        //     [
        //         'title' => 'Aetherial Trait',
        //         'category' => 'Power System',
        //         'content' => 'Aetherial Trait adalah kekuatan absolut berbasis resonansi jiwa. Membangkitkannya membawa risiko kehancuran diri jika tidak dikelola dengan benar.'
        //     ],
        //     [
        //         'title' => 'Kerajaan Lurn',
        //         'category' => 'Kingdom',
        //         'content' => 'Kerajaan yang sedang dilanda perang saudara antara Putri Aria dan kakaknya, Pangeran Julian. Kerajaan ini memiliki sejarah panjang dan berfokous ke arah ksatria, tetapi kini terancam oleh konflik internal dan ancaman eksternal.'
        //     ]
        // ];

        // foreach ($loreData as $lore) {
        //     WorldBuilding::create(array_merge($lore, ['novel_id' => $novel->id]));
        // }

        // 4. Characters
        $kaelen = Character::create([
            'novel_id' => $novel->id,
            'fullname' => 'Kaelen',
            'epithet' => 'Sang Malapetaka',
            'role' => 'Protagonist',
            'gender' => 'male',
            'height_cm' => 182,
            'appearance' => 'Rambut hitam panjang berantakan, tatapan tajan dan dingin dengan mata emas (sebelumnya ungu pekat), tubuh terlatih, berotot, dan memiliki beberapa bekas luka tempur, aura intimidasi ungu kehitaman.',
            'personality' => 'Rasional, dominan, arogan, dan dingin.',
            'motivation' => 'Mencari Kebebasan dan tidak ingin menjadi bagian dari bidak takdir.',
            'backstory' => 'Setelah menghancurkan dunianya dan membalaskan dendamnya, ia berakhir tersegel. Dia dibebaskan oleh entitas misterius ke dunia baru.',
        ]);

        $aria = Character::create([
            'novel_id' => $novel->id,
            'fullname' => 'Aria Lunareth',
            'title' => 'Putri Kerajaan Lurn',
            'role' => 'Deuteragonist',
            'gender' => 'female',
            'age' => 17,
            'height_cm' => 166,
            'appearance' => 'Memiliki mata hijau zamrud yang indah, tatapan sendu dan lebut, rambut keperakan yang panjang dan lurus, dan kulit porselen yang halus.',
            'personality' => 'Ceria, cerdas, penuh semangat, dan sangat peduli pada orang lain.',
            'motivation' => 'Menyelamatkan kerajaannya dari cengkraman kakak laki-lakinya yang arogan.',
            'backstory' => 'Putri mahkota Kerajaan Lurn yang sedang dalam bahaya karena perebutan takhta. Memiliki ikatan yang sangat kuat dengan ksatria pelindungnya Seren yang merupakan teman masa kecilnya.'
        ]);

        $seren = Character::create([
            'novel_id' => $novel->id,
            'fullname' => 'Seren Valorian',
            'title' => 'Kapten Batalion Divisi 3',
            'role' => 'Deuteragonist',
            'gender' => 'female',
            'age' => 19,
            'height_cm' => 174,
            'appearance' => 'Memiliki mata berwarna biru langit, tatapan tajam, rambut berwarna emas yang panjang dan bergelombang, dan kulit putih.',
            'personality' => 'Loyal, tegas, pemberani, dan memiliki tekad berapi-api.',
            'motivation' => 'Melindungi Putri Aria hingga titik darah penghabisan.',
            'backstory' => 'Ksatria kerajaan Lurn yang setia kepada Putri Aria dan berjuang untuk keadilan. Dia memiliki potensi untuk membangkitkan Aetherial Trait yang bisa menjadi kunci untuk menyelamatkan kerajaannya. Dia juga teman masa kecil Aria dan diutus oleh raja sebagai pengawal Putri Ara. Seren memiliki hubungan yang rumit dengan Kaelen, karena dia melihatnya sebagai ancaman.'
        ]);
    }
}