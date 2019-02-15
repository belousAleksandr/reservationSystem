<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Cinema;
use App\Repository\CinemaRepository;
use Doctrine\ORM\EntityManagerInterface;

class CinemaManager
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * CinemaManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $criteria
     * @param string|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Cinema[]
     */
    public function findBy(array $criteria = [], string $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->getCinemaRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @return CinemaRepository
     */
    private function getCinemaRepository(): CinemaRepository
    {
        return $this->entityManager->getRepository(Cinema::class);
    }
}