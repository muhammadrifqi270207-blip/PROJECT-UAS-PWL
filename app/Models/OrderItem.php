<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'tiket_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'tiket_id');
    }
}