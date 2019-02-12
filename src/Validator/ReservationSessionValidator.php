<?php

namespace App\Validator;

use App\Entity\Reservation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ReservationSessionValidator extends ConstraintValidator
{
    /**
     * @param Reservation $reservation
     * @param Constraint $constraint
     */
    public function validate($reservation, Constraint $constraint)
    {
        /* @var $constraint ReservationSession */

        $sessionCinema = $reservation->getSession()->getHall()->getCinema();
        if ($sessionCinema->getId() !== $reservation->getCinema()->getId()) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
