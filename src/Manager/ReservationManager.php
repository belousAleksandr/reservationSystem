<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Reservation;
use App\Event\ReservationCreatedEvent;
use App\Events;
use App\Util\PasswordUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReservationManager
{
    /** @var PasswordUpdater */
    private $passwordUpdater;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ReservationPriceManager */
    private $reservationPriceManager;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * ReservationManager constructor.
     *
     * @param PasswordUpdater $passwordUpdater
     * @param EntityManagerInterface $entityManager
     * @param ReservationPriceManager $reservationPriceManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        PasswordUpdater $passwordUpdater,
        EntityManagerInterface $entityManager,
        ReservationPriceManager $reservationPriceManager,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->passwordUpdater = $passwordUpdater;
        $this->entityManager = $entityManager;
        $this->reservationPriceManager = $reservationPriceManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $reservationId
     * @return Reservation|null
     */
    public function find($reservationId)
    {
        return $this->entityManager->find(Reservation::class, $reservationId);
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

        $event = new ReservationCreatedEvent($reservation);
        $this->eventDispatcher->dispatch(Events::RESERVATION_CREATED, $event);
    }

}