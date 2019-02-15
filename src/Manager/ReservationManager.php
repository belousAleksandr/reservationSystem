<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Reservation;
use App\Util\PasswordUpdater;
use Doctrine\ORM\EntityManagerInterface;

class ReservationManager
{
    /** @var PasswordUpdater */
    private $passwordUpdater;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ReservationPriceManager */
    private $reservationPriceManager;

    /**
     * ReservationManager constructor.
     *
     * @param PasswordUpdater $passwordUpdater
     * @param EntityManagerInterface $entityManager
     * @param ReservationPriceManager $reservationPriceManager
     */
    public function __construct(
        PasswordUpdater $passwordUpdater,
        EntityManagerInterface $entityManager,
        ReservationPriceManager $reservationPriceManager
    )
    {
        $this->passwordUpdater = $passwordUpdater;
        $this->entityManager = $entityManager;
        $this->reservationPriceManager = $reservationPriceManager;
    }

    /**
     * Create particular reservation
     *
     * @param Reservation $reservation
     */
    public function create(Reservation $reservation)
    {
        if ($reservation->getReservationOwner()->getId() === null) {
            $this->passwordUpdater->hashPassword($reservation->getReservationOwner());
        }

        $this->reservationPriceManager->calculateAndSetReservationPrice($reservation);

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }

}