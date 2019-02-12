<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ReservationSeats extends Constraint
{
    public $message = 'seat_may_not_be_reserved_twice';
}
