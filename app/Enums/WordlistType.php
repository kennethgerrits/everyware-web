<?php

namespace App\Enums;

class WordlistType
{
    const TEXT = 'TEXT';
    const TEXT_IMAGE = 'TEXT_IMAGE';
    const IMAGE = 'IMAGE';

    const KEYS = [
      self::TEXT => 'Alleen tekst',
      self::TEXT_IMAGE => 'Tekst gekoppeld aan een afbeelding',
      self::IMAGE => 'Alleen een afbeelding',
    ];

    const VALUES = [
        'Alleen tekst' => self::TEXT,
        'Tekst gekoppeld aan een afbeelding' => self::TEXT_IMAGE,
        'Alleen een afbeelding' => self::IMAGE,
    ];
}
