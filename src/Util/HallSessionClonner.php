<?php

declare(strict_types=1);

namespace App\Util;

use App\Entity\HallSession;
use App\Entity\Seat;
use Doctrine\ORM\EntityManagerInterface;

class HallSessionClonner
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

    public function copy(HallSession $session)
    {
        $copiedSession = clone $session;
        $this->copyRows($session, $copiedSession);

        $this->save($copiedSession);

        return $copiedSession;
    }

    /**
     * Clone rows from one session to another
     *
     * @param HallSession $session
     * @param HallSession $copiedSession
     */
    private function copyRows(HallSession $session, HallSession $copiedSession)
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

    private function save(HallSession $session)
    {
        $this->entityManager->persist($session);
        $this->entityManager->flush();
    }

}