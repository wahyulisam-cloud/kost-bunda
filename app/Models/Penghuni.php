<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    protected $table = 'penghuni';
    protected $primaryKey = 'id_penghuni';
    public $timestamps = false;

    // 🔥 DIPERBAIKI: tambahkan semua field yang bisa diupdate
    protected $fillable = [
        'nama_penghuni',
        'NIK',
        'no_hp',
        'tanggal_masuk',
        'status',
        'id_kamar'
    ];

    // RELASI KE KAMAR
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }
}