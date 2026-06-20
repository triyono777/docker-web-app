<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Note::query()->firstOrCreate(
            ['title' => 'Buat Dockerfile Laravel'],
            ['detail' => 'Install dependency Composer dan jalankan artisan serve di container.', 'completed' => true],
        );

        Note::query()->firstOrCreate(
            ['title' => 'Jalankan MySQL'],
            ['detail' => 'Service MySQL menyimpan database Laravel di volume Docker.', 'completed' => false],
        );

        Note::query()->firstOrCreate(
            ['title' => 'Cek phpMyAdmin'],
            ['detail' => 'Buka localhost:8080 untuk melihat tabel notes dan data aplikasi.', 'completed' => false],
        );
    }
}
