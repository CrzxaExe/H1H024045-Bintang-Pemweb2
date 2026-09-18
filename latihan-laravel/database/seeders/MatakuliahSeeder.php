<?php

namespace Database\Seeders;

use App\Models\Matakuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatakuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daftar = [
            ['kode' => 'TK245002', 'nama' => 'Sistem Mikocok', 'sks' => 2, 'semester' => 3 ],
            ['kode' => 'TK245003', 'nama' => 'Elektro Yoga', 'sks' => 3, 'semester' => 2 ],
            ['kode' => 'TK245004', 'nama' => 'Sistem Kendali Muani', 'sks' => 2, 'semester' => 2 ],
            ['kode' => 'TK245005', 'nama' => 'Internet of Thingtong', 'sks' => 3, 'semester' => 1 ],
            ['kode' => 'TK245006', 'nama' => 'Sistem Manual', 'sks' => 3, 'semester' => 1 ],
        ];

        foreach ($daftar as $item) {
            Matakuliah::create($item);
        }
    }
}
