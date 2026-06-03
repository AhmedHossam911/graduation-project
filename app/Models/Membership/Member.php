<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Auth\User;
use App\Models\System\Department;
use App\Models\Services\Membership;

/**
 * Represents the core profile of a fund participant.
 * Contains essential personal data (National ID, Date of Birth, Contact info) and links to
 * auxiliary data models (EmploymentInfo, FamilyInfo, Attachments, Membership status).
 */
class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'department_id', 'birth_date',
        'phone', 'address', 'marital_status', 'landline'
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

    /**
     * Scope to exclude the currently authenticated user's own member profile 
     * from lists, unless they are the first admin or browsing the member portal.
     */
    public function scopeExcludeSelf($query)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (!$user->isFirstAdmin() && !request()->is('member/*') && !request()->is('profile*')) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', '!=', $user->id)
                      ->orWhereNull('user_id');
                });
            }
        }
        return $query;
    }

    /**
     * Override resolveRouteBinding to enforce self-access restrictions
     * when implicit route model binding is used.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $member = parent::resolveRouteBinding($value, $field);

        if ($member && auth()->check()) {
            $user = auth()->user();
            if (!$user->isFirstAdmin() && !request()->is('member/*') && !request()->is('profile*')) {
                if ($member->user_id === $user->id) {
                    abort(403, 'لا تمتلك الصلاحية لعرض أو تعديل بياناتك الخاصة.');
                }
            }
        }

        return $member;
    }
}
