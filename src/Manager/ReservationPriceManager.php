<?php

declare(strict_types=1);

namespace App\Manager;


use App\Entity\Reservation;

class ReservationPriceManager
{
    /**
     * Calculate and set reservation price
     *
     * @param Reservation $reservation
     */
    public function calculateAndSetReservationPrice(Reservation $reservation)
    {
        $summaryPrice = $this->calculateSeatsPrice($reservation);

        if ($summaryPrice == 0) {
            throw new \LogicException('Reservation price may not be 0');
        }

        $reservation->setSummaryPrice($summaryPrice);
    }

    /**
     * Calculate seats price
     *
     * @param Reservation $reservation
     * @return float
     */
    private function calculateSeatsPrice(Reservation $reservation): float
    {
        $price = 0;
        foreach ($reservation->getSeats() as $seat) {
            $price += $seat->getPrice();
        }

        return $price;
    }
}