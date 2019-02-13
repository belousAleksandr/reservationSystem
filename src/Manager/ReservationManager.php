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

    /**
     * ReservationManager constructor.
     *
     * @param PasswordUpdater $passwordUpdater
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PasswordUpdater $passwordUpdater, EntityManagerInterface $entityManager)
    {
        $this->passwordUpdater = $passwordUpdater;
        $this->entityManager = $entityManager;
    }

    /**
     * Create particular reservation
     *
     * @param Reservation $reservation
     */
    public function create(Reservation $reservation)
    {
        $this->passwordUpdater->hashPassword($reservation->getReservationOwner());
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }

}