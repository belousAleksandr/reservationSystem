<?php

declare(strict_types=1);

namespace App\Security;


use App\Entity\ReservationOwner;
use App\Repository\ReservationOwnerRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ReservationOwnerProvider implements UserProviderInterface
{
    /** @var ReservationOwnerRepository */
    private $reservationOwnerRepository;

    /**
     * ReservationOwnerProvider constructor.
     * @param ReservationOwnerRepository $reservationOwnerRepository
     */
    public function __construct(ReservationOwnerRepository $reservationOwnerRepository)
    {
        $this->reservationOwnerRepository = $reservationOwnerRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->reservationOwnerRepository->findOneBy(['email' => $username]);

        if ($user === null) {
            throw new UsernameNotFoundException('Invalid user email');
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->reservationOwnerRepository->find($user);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return ReservationOwner::class === $class;
    }
}