<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'impersonator_id', 'action', 'table_name', 'record_id',
        'old_values', 'new_values', 'ip_address'
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function impersonator()
    {
        return $this->belongsTo(User::class, 'impersonator_id');
    }
}
