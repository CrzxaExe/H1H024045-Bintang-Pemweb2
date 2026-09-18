@extends('layouts.app')

@section('judul', 'Detail Mahasiswa')

@section('konten')
    <h1 class="h3 mb-4">Detail Mahasiswa</h1>
    <div class="card">
        <div class="card-body">
            <p class="mb-0">NIM yang diminta: <strong>{{ $mahasiswa->nim }}</strong></p>

            <div>
                <h2>{{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</h2>
                <div>
                    <span class="d-block">Prodi {{ $mahasiswa->programStudi->nama }}</span>
                    <span class="d-block">Angkatan {{ $mahasiswa->angkatan }}</span>
                    <span class="d-block">Ipk <strong>{{ $mahasiswa->ipk }}</strong></span>
                </div>

                <table class="table table-striped bg-white mt-4">
                    <tr>
                        <th>Matakuliah</th>
                        <th>Semester</th>
                        <th>Nilai</th>
                    </tr>
                    @foreach($mahasiswa->matakuliahs as $matakuliah)
                    <tr>
                        <td>{{ $matakuliah->nama }}</td>
                        <td>{{ $matakuliah->semester }}</td>
                        <td>{{ $matakuliah->pivot->nilai }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <a href="{{ route('mahasiswa.data') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
