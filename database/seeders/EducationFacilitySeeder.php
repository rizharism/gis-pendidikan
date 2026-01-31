<?php

namespace Database\Seeders;

use App\Models\EducationFacility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EducationFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = public_path('assets/dummy-data/dummy.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error("File dummy.json tidak ditemukan di: {$jsonPath}");
            return;
        }

        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (!isset($data['data'])) {
            $this->command->error("Format JSON tidak valid (key 'data' tidak ditemukan).");
            return;
        }

        $this->command->info("Sedang mengimpor " . count($data['data']) . " data fasilitas pendidikan...");

        foreach ($data['data'] as $item) {
            $name = $item['nama'];
            $klas = $this->detectKlas($name);
            
            // Deskripsi dengan sedikit bumbu
            $description = "{$name} merupakan salah satu lembaga pendidikan tingkat {$this->getKlasLabel($klas)} yang berlokasi di {$item['alamat']}. Sekolah ini berkomitmen untuk memberikan pendidikan berkualitas bagi masyarakat di sekitar Kota Blitar.";

            EducationFacility::create([
                'name' => $name,
                'klas' => $klas,
                'address' => $item['alamat'],
                'image' => null, // Set null agar menggunakan default.png yang baru dibuat
                'description' => $description,
                'latitude' => $item['lat'],
                'longitude' => $item['long'],
            ]);
        }

        $this->command->info("Berhasil mengimpor data!");
    }

    /**
     * Deteksi jenjang berdasarkan nama
     */
    private function detectKlas(string $name): string
    {
        $name = strtolower($name);

        if (str_contains($name, 'sd') || str_contains($name, 'mi ') || str_contains($name, 'mi_')) {
            return 'sd';
        }

        if (str_contains($name, 'smp') || str_contains($name, 'mts')) {
            return 'smp';
        }

        if (str_contains($name, 'sma') || str_contains($name, 'smk') || str_contains($name, 'man ') || str_contains($name, 'aliyah')) {
            return 'sma';
        }

        if (str_contains($name, 'univ') || str_contains($name, 'politeknik') || str_contains($name, 'stie') || str_contains($name, 'stikes') || str_contains($name, 'stkip') || str_contains($name, 'akn')) {
            return 'universitas';
        }

        return 'sd'; // Default
    }

    /**
     * Label jenjang untuk deskripsi
     */
    private function getKlasLabel(string $klas): string
    {
        return match ($klas) {
            'sd' => 'Dasar (SD/MI)',
            'smp' => 'Menengah Pertama (SMP/MTs)',
            'sma' => 'Menengah Atas (SMA/SMK/MA)',
            'universitas' => 'Perguruan Tinggi',
            default => 'Pendidikan',
        };
    }
}
