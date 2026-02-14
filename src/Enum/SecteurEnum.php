<?php
namespace App\Enum;

enum SecteurEnum: string
{
    case PUBLIC = 'Public';
    case PRIVATE = 'Privé';

    public static function getChoices(): array
    {
        return [
            'Public' => self::PUBLIC->value,
            'Privé' => self::PRIVATE->value,
        ];
    }
}