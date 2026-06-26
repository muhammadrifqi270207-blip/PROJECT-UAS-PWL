<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $table = 'tikets';

    protected $fillable = [
        'konser_id',
        'kategori',
        'harga',
        'kuota',
        'terjual',
    ];

    public function konser()
    {
        return $this->belongsTo(Konser::class);
    }
}