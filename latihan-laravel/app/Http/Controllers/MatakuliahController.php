<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    public function index()
    {
        $daftarMatakuliah = [
            ['kode' => 'TK245002', 'nama' => 'Sistem Mikocok', 'sks' => 2 ],
            ['kode' => 'TK245003', 'nama' => 'Elektro Yoga', 'sks' => 3 ],
            ['kode' => 'TK245004', 'nama' => 'Sistem Kendali Muani', 'sks' => 2 ],
            ['kode' => 'TK245005', 'nama' => 'Internet of Thingtong', 'sks' => 3 ],
            ['kode' => 'TK245006', 'nama' => 'Sistem Manual', 'sks' => 3 ],
        ];

        $kataKunci = request()->query('q', '');
        if ($kataKunci !== '') {
            $daftarMatakuliah = array_values(array_filter($daftarMatakuliah, function ($matakuliah) use ($kataKunci) {
                return stripos($matakuliah['kode'], $kataKunci) !== false
                    || stripos($matakuliah['nama'], $kataKunci) !== false;
            }));
        }

        return view('matakuliah.index', ['daftarMatakuliah' => $daftarMatakuliah]);
    }

    public function show(string $kode)
    {
        return view('matakuliah.show', ['kode' => $kode]);
    }

    public function cari(Request $request)
    {
        $kataKunci = $request->query('q', '');
        return response()->json([
            'kata_kunci' => $kataKunci,
            'metode' => $request->method(),
            'path' => $request->path(),
        ]);
    }
}
