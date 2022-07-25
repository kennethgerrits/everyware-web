<?php

namespace App\Enums;

class Difficulty
{
    const EASY = 1;
    const MIDDLE = 2;
    const HIGH = 3;

    const KEYS = [
      self::EASY => 'Niveau 1a',
      self::MIDDLE => 'Niveau 1b',
      self::HIGH => 'Niveau 1c',
    ];

    const VALUES = [
        'Niveau 1a' => self::EASY,
        'Niveau 1b' => self::MIDDLE,
        'Niveau 1c' => self::HIGH,
    ];
}
