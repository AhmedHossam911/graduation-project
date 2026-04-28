<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentInfo extends Model
{
    use HasFactory;

    protected $table = 'employment_info';

    protected $fillable = [
        'member_id', 'workplace', 'job_title', 'financial_category', 'join_date',
        'retirement_date', 'starting_salary'
    ];

    protected $casts = [
        'join_date' => 'date',
        'retirement_date' => 'date',
        'starting_salary' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
