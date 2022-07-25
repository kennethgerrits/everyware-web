<?php

namespace App\Enums;

class QuestionType
{
    const STATIC_IMAGE = 'STATIC_IMAGE';
    const STATIC_TEXT = 'STATIC_TEXT';
    const LISTENING = 'LISTENING';
    const ARITHMETIC_LISTENING = 'ARITHMETIC_LISTENING';
    const ARITHMETIC_IMAGE = 'ARITHMETIC_IMAGE';
    const ARITHMETIC_SUM_TEXT = 'ARITHMETIC_SUM_TEXT';
    const ARITHMETIC_SUM_IMAGE = 'ARITHMETIC_SUM_IMAGE';
    const DRAG_IMAGE = 'DRAG_IMAGE';

    const KEYS = [
      self::STATIC_IMAGE => 'Een afbeelding',
      self::STATIC_TEXT => 'Een tekst',
      self::LISTENING => 'Een luister fragment',
      self::ARITHMETIC_LISTENING => 'Luister rekensom',
      self::ARITHMETIC_IMAGE => 'Afbeeldingen tellen',
      self::ARITHMETIC_SUM_TEXT => 'Tekstuele rekensom',
      self::ARITHMETIC_SUM_IMAGE => 'Grafische rekensom',
      self::DRAG_IMAGE => 'Slepen',
    ];

    const VALUES = [
        'Een luister fragment' => self::LISTENING,
        'Een afbeelding' => self::STATIC_IMAGE,
        'Luister rekensom' => self::ARITHMETIC_LISTENING,
        'Een tekst' => self::STATIC_TEXT,
        'Afbeeldingen tellen' => self::ARITHMETIC_IMAGE,
        'Tekstuele rekensom' => self::ARITHMETIC_SUM_TEXT,
        'Grafische rekensom' => self::ARITHMETIC_SUM_IMAGE,
        'Slepen' => self::DRAG_IMAGE,
    ];
}
