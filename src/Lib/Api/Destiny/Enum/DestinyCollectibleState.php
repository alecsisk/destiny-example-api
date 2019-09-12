<?php

namespace App\Lib\Api\Destiny\Enum;

interface DestinyCollectibleState
{
    public const NONE = 0;
    public const NOT_ACQUIRED = 1;
    public const OBSCURED = 2;
    public const INVISIBLE = 4;
    public const CANNOT_AFFORD_MATERIAL_REQUIREMENTS = 8;
    public const INVENTORY_SPACE_UNAVAILABLE = 16;
    public const UNIQUENESS_VIOLATION = 32;
    public const PURCHASE_DISABLED = 64;
}