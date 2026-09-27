<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProgramStudiController extends Controller
{
    public function mahasiswa(Request $request, ProgramStudi $programStudi): JsonResponse
    {
        $query = Mahasiswa::query()
            ->where('program_studi_id', $programStudi->id)
            ->with('programStudi');

        $perPage = min($request->integer('per_page', $request->integer('per_halaman', 10)), 100);
        $paginator = $query->paginate($perPage);

        $fieldsParam = $request->query('fields');
        $fieldList = $fieldsParam ? array_values(array_filter(array_map('trim', explode(',', $fieldsParam)))) : null;

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

        return response()->json([
            'sukses' => true,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }
}
