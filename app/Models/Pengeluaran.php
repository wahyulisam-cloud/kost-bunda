<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';
    public $timestamps = false;

  protected $fillable = [
    'tanggal',
    'tanggal_bayar',
    'id_kategori',
    'keterangan',
    'nominal',
    'status'
];


    // RELASI KE KATEGORI
    public function kategori()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'id_kategori');
    }
}
