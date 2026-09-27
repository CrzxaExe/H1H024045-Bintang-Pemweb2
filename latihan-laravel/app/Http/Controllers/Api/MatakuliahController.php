<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMatakuliahRequest;
use App\Http\Requests\UpdateMatakuliahRequest;
use App\Http\Resources\MatakuliahResource;
use App\Models\Matakuliah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = Matakuliah::query();

        if ($request->filled('cari')) {
            $keyword = $request->query('cari');
            $query->where(function ($sub) use ($keyword) {
                $sub->where('nama', 'like', '%' . $keyword . '%')
                    ->orWhere('kode', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->integer('semester'));
        }

        $sort = $request->query('urut', 'nama');
        $direction = $request->query('arah', 'asc');
        $allowedColumns = ['kode', 'nama', 'sks', 'semester'];

        if (in_array($sort, $allowedColumns, true)) {
            $query->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
        }

        $perPage = min($request->integer('per_halaman', 10), 100);

        return MatakuliahResource::collection($query->paginate($perPage));
    }

    public function store(StoreMatakuliahRequest $request): JsonResponse
    {
        $matakuliah = Matakuliah::create($request->validated());

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data matakuliah berhasil dibuat',
            'data' => new MatakuliahResource($matakuliah),
        ], 201);
    }

    public function show(Matakuliah $matakuliah): JsonResponse
    {
        return response()->json([
            'sukses' => true,
            'data' => new MatakuliahResource($matakuliah),
        ]);
    }

    public function update(UpdateMatakuliahRequest $request, Matakuliah $matakuliah): JsonResponse
    {
        $matakuliah->update($request->validated());

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data matakuliah berhasil diperbarui',
            'data' => new MatakuliahResource($matakuliah->fresh()),
        ]);
    }

    public function destroy(Matakuliah $matakuliah): JsonResponse
    {
        $matakuliah->delete();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data matakuliah berhasil dihapus',
        ]);
    }
}
