<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'contact_no',
        'email',
        'location',
        'vendor_category',
        'service',
        'capacity',
        'price',
        'packages',
        'media_files',
        'owner_name',
        'owner_number',
        'owner_email',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'media_files' => 'array',
    ];

    public const CATEGORIES = [
        'Banquet Hall / Venue',
        'Wedding Decorator',
        'Catering & Food Service',
        'Photographer & Cinematographer',
        'DJ & Live Entertainment',
        'Bridal Makeup & Hair',
        'Bridal & Groom Wear',
        'Mehendi Artist',
        'Wedding Planner & Coordinator',
        'Cakes & Desserts',
        'Invitations & Favors',
        'Other Services',
    ];

    public const STATUSES = [
        'pending' => 'Pending Review',
        'contacted' => 'Contacted',
        'approved' => 'Approved / Added',
        'rejected' => 'Rejected',
    ];

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'contacted' => ['class' => 'bg-amber-100 text-amber-800 border-amber-200', 'label' => 'Contacted'],
            'approved' => ['class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Approved / Added'],
            'rejected' => ['class' => 'bg-rose-100 text-rose-800 border-rose-200', 'label' => 'Rejected'],
            default => ['class' => 'bg-blue-100 text-blue-800 border-blue-200', 'label' => 'Pending Review'],
        };
    }
}
