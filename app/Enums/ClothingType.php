<?php

namespace App\Enums;

enum ClothingType: string
{
    case TOP = 'top';
    case OUTERWEAR = 'outerwear';
    case BOTTOM = 'bottom';
    case FULL_BODY = 'full_body'; // dress or jumpsuit
    case SHOES = 'shoes';
    case ACCESSORY = 'accessory';
    case HAT = 'hat';
}
