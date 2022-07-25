<?php

namespace App\Enums;

class Role
{
    const STUDENT = "STUDENT";
    const TEACHER = "TEACHER";
    const ADMIN = "ADMIN";

    const KEYS = [
        self::STUDENT => 'Leerling',
        self::TEACHER => 'Leraar',
        self::ADMIN => 'Directie',
    ];

    const VALUES = [
        'Leerling' => self::STUDENT,
        'Leraar' => self::TEACHER,
        'Directie' => self::ADMIN
    ];
}
