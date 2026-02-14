<?php
namespace App\Enum;

enum EtatEtabEnum: string
{
    case OUVERT = "OUVERT";
    case A_OUVRIR = "A OUVRIR";
    case A_FERMER = "A FERMER";

    public static function getChoices(): array
    {
        return [
            'Ouvert' => self::OUVERT->value,
            'A Ouvrir' => self::A_OUVRIR->value,
            'A Fermer' => self::A_FERMER->value,
        ];
    }
}
