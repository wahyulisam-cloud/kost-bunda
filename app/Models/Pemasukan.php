<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Penghuni;
use Carbon\Carbon;


class Pemasukan extends Model
{
    protected $table = 'pemasukan';
    protected $primaryKey = 'id_pemasukan';
    public $timestamps = false;

    protected $fillable = [
        'id_penghuni',
        'bulan',
        'tahun',
        'tanggal_bayar',
        'nominal',
        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI KE PENGHUNI
    |--------------------------------------------------------------------------
    */
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR NAMA BULAN
    |--------------------------------------------------------------------------
    */
    public function getNamaBulanAttribute()
    {
        return Carbon::create($this->tahun, $this->bulan)
            ->locale('id')
            ->translatedFormat('F Y');
    }
}
