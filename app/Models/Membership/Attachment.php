<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'type', 'file_path'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
