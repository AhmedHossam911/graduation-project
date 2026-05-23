<?php
 
 namespace App\Models\Services;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
 class Claim extends Model
 {
     use HasFactory;
 
     protected $fillable = [
         'membership_id', 'type', 'amount', 'status', 'attachment_receipt'
     ];
 
     public const CLAIM_TYPES = [
        'retirement'              => 'بلوغ سن التقاعد القانوني',
        'resignation'             => 'استقالة',
        'early_retirement'        => 'معاش مبكر',
        'withdrawal'              => 'انسحاب',
        'expulsion'               => 'فصل',
        'professional_disability' => 'عجز مهني',
        'transfer'                => 'نقل',
        'death'                   => 'وفاة',
    ];
 
     protected $casts = [
         'amount' => 'decimal:2',
     ];
 
     public function membership()
     {
         return $this->belongsTo(Membership::class);
     }
 }
