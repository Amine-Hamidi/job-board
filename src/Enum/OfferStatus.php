<?php

namespace App\Enum;

enum OfferStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case PENDING = 'pending';
    case EXPIRED = 'expired';
}
