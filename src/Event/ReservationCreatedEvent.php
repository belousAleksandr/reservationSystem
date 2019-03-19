<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Reservation;
use Symfony\Component\EventDispatcher\Event;

class ReservationCreatedEvent extends Event
{
    /** @var Reservation */
    private $reservation;

    /**
     * ReservationCreatedEvent constructor.
     *
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * @return Reservation
     */
    public function getReservation(): Reservation
    {
        return $this->reservation;
    }
}