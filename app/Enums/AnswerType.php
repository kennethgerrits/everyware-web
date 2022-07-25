<?php

namespace App\Enums;

class AnswerType
{
    const MULTIPLE_CHOICE = 'MULTIPLE_CHOICE';
    const WRITING = 'WRITING';
    const VOICE = 'VOICE';

    const KEYS = [
        self::MULTIPLE_CHOICE => 'Multiple choice tekst',
        self::WRITING => 'Schrijven',
        self::VOICE => 'Spraak',
    ];

    const VALUES = [
        'Multiple choice tekst' => self::MULTIPLE_CHOICE,
        'Schrijven' => self::WRITING,
        'Spraak' => self::VOICE,
    ];
}
