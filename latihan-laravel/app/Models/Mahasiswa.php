<?php

namespace App\Models;

use Database\Factories\MahasiswaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mahasiswa extends Model
{
    /** @use HasFactory<MahasiswaFactory> */
    use HasFactory;

    protected $table = 'mahasiswas';
    protected $fillable = [
        'program_studi_id',
        'nim',
        'nama',
        'email',
        'angkatan',
        'ipk',
        'aktif',
    ];


    protected function casts(): array
    {
        return [
            'angkatan' => 'integer',
            'ipk' => 'decimal:2',
            'aktif' => 'boolean',
        ];
    }

    public function getAllTopIpk(int $limit = 10)
    {
        return self::query()
            ->with('programStudi')
            ->whereHas('programStudi', function ($query) {
                $query->where('nama', 'Teknik Komputer');
            })
            ->orderByDesc('ipk')
            ->limit($limit)
            ->get();
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function matakuliahs(): BelongsToMany
    {
        return $this->belongsToMany(Matakuliah::class)
            ->withPivot('nilai')
            ->withTimestamps();
    }
}
