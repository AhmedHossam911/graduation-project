<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Auth\User;
use App\Models\System\Department;
use App\Models\Services\Membership;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'department_id', 'full_name', 'national_id', 'birth_date',
        'phone', 'address'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employmentInfo()
    {
        return $this->hasOne(EmploymentInfo::class);
    }

    public function familyInfo()
    {
        return $this->hasOne(FamilyInfo::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function membershipInfo()
    {
        return $this->hasOne(Membership::class);
    }
}
