<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $guarded = [];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
    
    public function divisions()
    {
        return $this->belongsToMany(\App\Models\System\Division::class, 'member_divisions');
    }
    
    public function employments()
    {
        return $this->hasMany(Employment::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function documents()
    {
        return $this->morphMany(\App\Models\System\Document::class, 'documentable');
    }
}
