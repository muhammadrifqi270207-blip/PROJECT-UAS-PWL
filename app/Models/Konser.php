<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konser extends Model
{
    protected $fillable = [
        'nama_konser', 'artis', 'venue',
        'tanggal', 'jam', 'poster', 'status',
        'deskripsi', 'maps_url', 'genre',
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    public function getTotalTiketAttribute()
    {
        return $this->tikets()->sum('kuota');
    }

    public function getTotalTerjualAttribute()
    {
        return $this->tikets()->sum('terjual');
    }
}