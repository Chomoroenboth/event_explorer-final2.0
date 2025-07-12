<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRequest extends Model
{
    protected $table = 'event_requests';

    const APPROVAL_STATUS_PENDING = 'pending';
    const APPROVAL_STATUS_APPROVED = 'approved';
    const APPROVAL_STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'location',
        'area',
        'event_type',
        'category',
        'format',
        'is_free',
        'price',
        'requester_email',
        'requester_phone',
        'image',
        'reference_link',
        'requested_by',
        'approved_by',
        'approval_status',
    ];

    public static function getAreas(): array
    {
        return [
            'Koh Pich',
            'Toul Kork',
            'Bak Touk',
            'Toul Tompong',
            'Steung Meanchey',
            'Boeung Keng Kang',
        ];
    }

    public static function getCategories(): array
    {
        return [
            'Technology',
            'Sport',
            'Community',
            'Business/Professional',
            'Educational',
            'Charity & Fundraising',
            'Entertainment & Art',
        ];
    }

    public static function getEventTypes(): array
    {
        return [
            'Webinar',
            'Conference',
            'Show',
            'Auction',
            'Festival',
            'Exhibition',
            'Job Fairs',
            'Competition',
        ];
    }

    public static function getFormats(): array
    {
        return [
            'Virtual',
            'In-Person',
            'Hybrid',
        ];
    }

    public static function getFees(): array
    {
        return [
            'Free Entry',
        ];
    }
}
