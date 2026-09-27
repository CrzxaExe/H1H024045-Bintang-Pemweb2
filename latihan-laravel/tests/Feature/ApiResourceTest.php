<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\ProgramStudi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_and_create_matakuliah(): void
    {
        $response = $this->getJson('/api/matakuliah');
        $response->assertOk();

        $payload = [
            'kode' => 'MK-101',
            'nama' => 'Pemrograman Web',
            'sks' => 3,
            'semester' => 3,
        ];

        $createResponse = $this->postJson('/api/matakuliah', $payload);
        $createResponse->assertStatus(201)
            ->assertJsonPath('sukses', true)
            ->assertJsonPath('data.kode', 'MK-101');
    }

    public function test_can_get_mahasiswa_by_program_studi_with_pagination_and_fields(): void
    {
        $programStudi = ProgramStudi::create([
            'kode' => 'SI',
            'nama' => 'Sistem Informasi',
            'jenjang' => 'S1',
        ]);

        Mahasiswa::create([
            'program_studi_id' => $programStudi->id,
            'nim' => '2024001',
            'nama' => 'Andi',
            'email' => 'andi@example.com',
            'angkatan' => 2024,
            'ipk' => 3.75,
            'aktif' => true,
        ]);

        Mahasiswa::create([
            'program_studi_id' => $programStudi->id,
            'nim' => '2024002',
            'nama' => 'Budi',
            'email' => 'budi@example.com',
            'angkatan' => 2024,
            'ipk' => 3.80,
            'aktif' => true,
        ]);

        $response = $this->getJson('/api/program-studi/' . $programStudi->id . '/mahasiswa?fields=nim,nama&per_page=1');

        $response->assertOk();
        $response->assertJsonPath('data.0.nim', '2024001');
        $response->assertJsonMissingPath('data.0.email');
        $response->assertJsonPath('meta.per_page', 1);
    }

    public function test_can_update_and_delete_matakuliah(): void
    {
        $matakuliah = Matakuliah::create([
            'kode' => 'MK-202',
            'nama' => 'Basis Data',
            'sks' => 2,
            'semester' => 4,
        ]);

        $updateResponse = $this->putJson('/api/matakuliah/' . $matakuliah->id, [
            'kode' => 'MK-202-UPDATE',
            'nama' => 'Basis Data Lanjut',
            'sks' => 3,
            'semester' => 5,
        ]);

        $updateResponse->assertOk()
            ->assertJsonPath('sukses', true)
            ->assertJsonPath('data.nama', 'Basis Data Lanjut');

        $deleteResponse = $this->deleteJson('/api/matakuliah/' . $matakuliah->id);
        $deleteResponse->assertOk()
            ->assertJsonPath('sukses', true);
    }
}
