<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\MahasiswaMatakuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MahasiswaMatakuliah>
 */
class MahasiswaMatakuliahFactory extends Factory
{
    protected $model = MahasiswaMatakuliah::class;

    public function definition(): array
    {
        $matakuliah = Matakuliah::query()->inRandomOrder()->first() ?? Matakuliah::factory()->create();
        $semester = $matakuliah->semester ?? 1;

        return [
            'mahasiswa_id' => Mahasiswa::query()->inRandomOrder()->value('id') ?? Mahasiswa::factory()->create()->id,
            'matakuliah_id' => $matakuliah->id,
            'nilai' => fake()->randomFloat(2, 60 + ($semester - 1) * 5, 95),
        ];
    }
}
