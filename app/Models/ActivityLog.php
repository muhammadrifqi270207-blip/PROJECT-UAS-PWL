<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'aktivitas', 'ip_address'];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper static untuk mencatat log dengan praktis
    public static function catat($userId, $aktivitas)
    {
        self::create([
            'user_id'    => $userId,
            'aktivitas'  => $aktivitas,
            'ip_address' => request()->ip(),
        ]);
    }
}