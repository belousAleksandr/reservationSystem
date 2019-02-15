<?php

namespace App\Tests;

use App\Entity\Reservation;
use App\Entity\Seat;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use App\Manager\ReservationPriceManager;

class ReservationPriceManagerTest extends TestCase
{
    /**
     * @test
     */
    public function calculateAndSetReservationPrice()
    {
        $reservationMock = $this->createMock(Reservation::class);
        $reservationMock->method('getSeats')->willReturn(new ArrayCollection([
            $this->createSeatMock(10),
            $this->createSeatMock(0),
            $this->createSeatMock(1),

        ]));

        $reservationMock->expects(self::once())->method('setSummaryPrice')->with(11);

        $reservationManager = new ReservationPriceManager();
        $reservationManager->calculateAndSetReservationPrice($reservationMock);
    }

    /**
     * @test
     */
    public function calculateAndSetReservationPriceWithEmptySeats()
    {
        $reservationMock = $this->createMock(Reservation::class);
        $reservationMock->method('getSeats')->willReturn(new ArrayCollection([]));

        $this->expectException(\LogicException::class);

        $reservationManager = new ReservationPriceManager();
        $reservationManager->calculateAndSetReservationPrice($reservationMock);
    }

    /**
     * @test
     */
    public function calculateAndSetReservationPriceWithWrongSeatsPrices()
    {
        $reservationMock = $this->createMock(Reservation::class);
        $reservationMock->method('getSeats')->willReturn(new ArrayCollection([
            $this->createSeatMock(0),
            $this->createSeatMock(0),
            $this->createSeatMock(0)
        ]));

        $this->expectException(\LogicException::class);

        $reservationManager = new ReservationPriceManager();
        $reservationManager->calculateAndSetReservationPrice($reservationMock);
    }

    /**
     * Create a mock object for Seat
     *
     * @param $price
     * @return Seat|\PHPUnit\Framework\MockObject\MockObject
     */
    private function createSeatMock($price)
    {
        $seatMock = $this->createMock(Seat::class);
        $seatMock->method('getPrice')->willReturn($price);

        return $seatMock;
    }
}
