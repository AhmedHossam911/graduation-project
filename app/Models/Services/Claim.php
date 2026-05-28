<?php
 
 namespace App\Models\Services;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
/**
 * Represents an insurance claim filed by a member (or their beneficiaries) upon the termination of their service.
 * Tracks the reason for the claim (e.g., retirement, resignation, death) and the final disbursed amount.
 */
class Claim extends Model
{
     use HasFactory;
 
     protected $fillable = [
         'membership_id', 'type', 'amount', 'status', 'attachment_receipt'
     ];
 
     /**
      * Predefined claim types matching the fund's official bylaws.
      */
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
