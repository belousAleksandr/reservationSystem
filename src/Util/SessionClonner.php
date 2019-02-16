<?php

declare(strict_types=1);

namespace App\Util;

use App\Entity\Seans;
use App\Entity\Seat;
use Doctrine\ORM\EntityManagerInterface;

class SessionClonner
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * SessionClonner constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function copy(Seans $session)
    {
        $copiedSession = clone $session;
        $this->copyRows($session, $copiedSession);

        $this->save($copiedSession);

        return $copiedSession;
    }

    /**
     * Clone rows from one session to another
     *
     * @param Seans $session
     * @param Seans $copiedSession
     */
    private function copyRows(Seans $session, Seans $copiedSession)
    {
        foreach ($session->getRows() as $row) {
            $copiedRow = clone $row;
            foreach ($row->getSeats() as $seat) {
                $copiedRow->addSeat($this->copySeat($seat));
            }

            $copiedSession->addRow($copiedRow);
        }
    }

    /**
     * Clone provided Seat with some data updates
     *
     * @param Seat $seat
     * @return Seat
     */
    private function copySeat(Seat $seat): Seat
    {
        $copiedSeat = clone $seat;
        $copiedSeat->setReservation(null);

        return $copiedSeat;
    }

    private function save(Seans $session)
    {
        $this->entityManager->persist($session);
        $this->entityManager->flush();
    }

}