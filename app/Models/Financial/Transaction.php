<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_type', 'reference_id', 'amount', 'type', 'method', 'receipt_no'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function reference()
    {
        return $this->morphTo();
    }
}
