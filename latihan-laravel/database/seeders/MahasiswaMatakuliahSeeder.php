<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Database\Seeder;

class MahasiswaMatakuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswas = Mahasiswa::all();
        $matakuliahs = Matakuliah::all();

        if ($mahasiswas->isEmpty() || $matakuliahs->isEmpty()) {
            return;
        }

        foreach ($mahasiswas as $mahasiswa) {
            foreach ($matakuliahs as $matakuliah) {
                if(date("Y") - $mahasiswa->angkatan < $matakuliah->semester) continue;
                $nilai = fake()->randomFloat(2, 40 + ($matakuliah->semester - 1) * 5, 95);

                $mahasiswa->matakuliahs()->syncWithoutDetaching([
                    $matakuliah->id => [
                        'nilai' => $nilai,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }
    }
}
