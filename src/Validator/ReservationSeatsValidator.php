<?php

namespace App\Validator;

use App\Repository\SeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ReservationSeatsValidator extends ConstraintValidator
{
    /** @var SeatRepository */
    private $seatRepository;

    /**
     * ReservationSeatsValidator constructor.
     *
     * @param SeatRepository $seatRepository
     */
    public function __construct(SeatRepository $seatRepository)
    {
        $this->seatRepository = $seatRepository;
    }

    /**
     * @param ArrayCollection $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $reservedSeads = $this->seatRepository->findReservedSeats($value->toArray());
        if (!empty($reservedSeads)) {
            /* @var $constraint ReservationSeats */
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
