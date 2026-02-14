<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateOpeningConstraint extends Constraint
{
    public $message = 'La date d\'ouverture ne peut pas être inférieure à la date actuelle si l\'état est "À ouvrir" ou "A Fermer"';
}
