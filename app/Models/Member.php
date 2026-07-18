<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'user_id',
        'photo',
        'name',
        'name_bn',
        'father_name',
        'father_name_bn',
        'mother_name',
        'mother_name_bn',
        'date_of_birth',
        'gender',
        'blood_group',
        'mobile',
        'email',
        'national_id',
        'passport_number',
        'iqama_number',
        'occupation',
        'occupation_bn',
        'designation',
        'company_name',
        'present_address',
        'present_address_bn',
        'permanent_address',
        'permanent_address_bn',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'join_date',
        'member_type',
        'status',
        'position',
        'nominee_name',
        'nominee_relation',
        'nominee_phone',
        'qr_code',
        'referrer_member_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
        'status' => 'boolean',
    ];

    const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
        'expired' => 'Expired',
        'deceased' => 'Deceased',
    ];

    const MEMBER_TYPES = [
        'general' => 'General Member',
        'life' => 'Life Member',
        'honorary' => 'Honorary Member',
        'founder' => 'Founder Member',
        'associate' => 'Associate Member',
    ];

    const GENDERS = [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
    ];

    const BLOOD_GROUPS = [
        'A+' => 'A+',
        'A-' => 'A-',
        'B+' => 'B+',
        'B-' => 'B-',
        'O+' => 'O+',
        'O-' => 'O-',
        'AB+' => 'AB+',
        'AB-' => 'AB-',
    ];

    const POSITIONS = [
        'member' => 'Member',
        'executive' => 'Executive Member',
        'secretary' => 'Secretary',
        'vice_president' => 'Vice President',
        'president' => 'President',
        'advisor' => 'Advisor',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            if (empty($member->member_id)) {
                $member->member_id = self::generateMemberId();
            }
        });

        static::created(function ($member) {
            $member->update(['qr_code' => self::generateQrCode($member)]);
        });
    }

    public static function generateMemberId(): string
    {
        $year = date('Y');
        $lastMember = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastMember) {
            $lastNumber = (int) substr($lastMember->member_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'FMS-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function generateQrCode(Member $member): string
    {
        $data = json_encode([
            'id' => $member->member_id,
            'name' => $member->name,
            'phone' => $member->mobile,
            'join_date' => $member->join_date?->format('Y-m-d'),
        ]);

        return base64_encode($data);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referrer()
    {
        return $this->belongsTo(Member::class, 'referrer_member_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
              ->orWhere('member_id', 'like', '%' . $term . '%')
              ->orWhere('mobile', 'like', '%' . $term . '%')
              ->orWhere('email', 'like', '%' . $term . '%');
        });
    }

    public function getFullAddressAttribute(): string
    {
        return $this->present_address ?? $this->permanent_address ?? '';
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/members/' . $this->photo);
        }
        return asset('images/avatar.png');
    }
}
