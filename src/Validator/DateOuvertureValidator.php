<?php

namespace App\Validator;

use App\Entity\Etablissement;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class DateOpeningValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // Vérification du type de contrainte
        if (!$constraint instanceof DateOpeningConstraint) {
            throw new UnexpectedTypeException($constraint, DateOpeningConstraint::class);
        }

        if ($value === null) {
            return;
        }
        $etablissement = $this->context->getObject();
        if ($etablissement instanceof Etablissement) {
            if (in_array($etablissement->getEtatEtablissementLibe(), ['A OUVRIR', 'A FERMER'])) {
                // Comparer la date d'ouverture avec la date actuelle
                $today = new \DateTime();
                $today->setTime(0, 0, 0);

                if ($value < $today) {
                    $this->context->buildViolation($constraint->message)
                        ->addViolation();
                }
            }
        }
    }
}
