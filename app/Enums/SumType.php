<?php

namespace App\Enums;

class SumType
{
    const PLUS = "PLUS";
    const MINUS = "MINUS";
    const DIVIDE = "DIVIDE";
    const TIMES = "TIMES";

    const KEYS = [
        self::PLUS => "Optellen",
        self::MINUS => "Aftrekken",
        self::DIVIDE => "Delen",
        self::TIMES => "Vermenigvuldigen",
    ];

    const VALUES = [
        "Optellen" => self::PLUS,
        "Aftrekken" => self::MINUS,
        "Delen" => self::DIVIDE,
        "Vermenigvuldigen" => self::TIMES,
    ];
}
