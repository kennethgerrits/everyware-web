<?php

namespace App\Enums;

class StatisticType
{
    const GENERAL = "GENERAL";
    const DETAIL = "DETAIL";

    const KEYS = [
        self::GENERAL => 'Algemeen',
        self::DETAIL => 'Details',
    ];

    const VALUES = [
        'Algemeen' => self::GENERAL,
        'Details' => self::DETAIL,
    ];
}
