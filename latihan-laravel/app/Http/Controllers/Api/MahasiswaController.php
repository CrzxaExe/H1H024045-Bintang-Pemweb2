<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $kueri = Mahasiswa::query()->with('programStudi');

        if ($request->filled('cari')) {
            $kataKunci = $request->query('cari');
            $kueri->where(function ($sub) use ($kataKunci) {
                $sub->where('nama', 'like', '%' . $kataKunci . '%')
                    ->orWhere('nim', 'like', '%' . $kataKunci . '%');
            });
        }

        if ($request->filled('angkatan')) {
            $kueri->where('angkatan', $request->integer('angkatan'));
        }

        if ($request->filled('program_studi_id')) {
            $kueri->where('program_studi_id', $request->integer('program_studi_id'));
        }

        $urutan = $request->query('urut', 'nama');
        $arah = $request->query('arah', 'asc');
        $kolomDiizinkan = ['nama', 'nim', 'angkatan', 'ipk'];

        if (in_array($urutan, $kolomDiizinkan, true)) {
            $kueri->orderBy($urutan, $arah === 'desc' ? 'desc' : 'asc');
        }

        $perHalaman = min($request->integer('per_halaman', $request->integer('per_page', 10)), 100);

        $fieldsParam = $request->query('fields');
        $fieldList = $fieldsParam ? array_values(array_filter(array_map('trim', explode(',', $fieldsParam)))) : null;

        $paginator = $kueri->paginate($perHalaman);

        if ($fieldList !== null && $fieldList !== []) {
            $allowedFields = ['id', 'nim', 'nama', 'email', 'angkatan', 'ipk', 'aktif', 'program_studi'];
            $finalFields = array_values(array_unique(array_filter($fieldList, fn ($field) => in_array($field, $allowedFields, true))));

            $data = $paginator->getCollection()->map(function ($mahasiswa) use ($finalFields) {
                $payload = [];

                foreach ($finalFields as $field) {
                    if ($field === 'program_studi') {
                        $payload['program_studi'] = [
                            'id' => $mahasiswa->programStudi?->id,
                            'kode' => $mahasiswa->programStudi?->kode,
                            'nama' => $mahasiswa->programStudi?->nama,
                        ];
                        continue;
                    }

                    if (isset($mahasiswa->{$field})) {
                        $payload[$field] = $mahasiswa->{$field};
                    }
                }

                return $payload;
            })->values();

            return response()->json([
                'sukses' => true,
                'data' => $data,
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'last_page' => $paginator->lastPage(),
                ],
            ]);
        }

        return MahasiswaResource::collection($paginator);
    }

    public function store(StoreMahasiswaRequest $request): JsonResponse
    {
        $mahasiswa = Mahasiswa::create($request->validated());
        $mahasiswa->load('programStudi');

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mahasiswa berhasil dibuat',
            'data' => new MahasiswaResource($mahasiswa),
        ], 201);
    }

    public function show(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->load('programStudi');

        return response()->json([
            'sukses' => true,
            'data' => new MahasiswaResource($mahasiswa),
        ]);
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->update($request->validated());
        $mahasiswa->load('programStudi');

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mahasiswa berhasil diperbarui',
            'data' => new MahasiswaResource($mahasiswa),
        ]);
    }

    public function destroy(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->delete();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mahasiswa berhasil dihapus',
        ]);
    }
}
