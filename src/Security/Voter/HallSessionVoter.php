<?php

namespace App\Security\Voter;

use App\Entity\HallSession;
use App\Repository\SeatRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class HallSessionVoter extends Voter
{
    const DELETE = 'ROLE_ADMIN_HALL_SESSION_DELETE';

    /** @var SeatRepository */
    private $seatRepository;

    /**
     * HallSessionVoter constructor.
     *
     * @param SeatRepository $seatRepository
     */
    public function __construct(SeatRepository $seatRepository)
    {
        $this->seatRepository = $seatRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE])
            && $subject instanceof HallSession;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($subject);
                break;
        }

        return false;
    }

    /**
     * Checks if we can remove particular HallSession
     *
     * @param HallSession $hallSession
     * @return bool
     */
    private function canDelete(HallSession $hallSession): bool
    {
        // In case if HallSession has at least one booked seat than we decline remove this HallSession
        if (\count($this->seatRepository->findReservedSeatsByHallSession($hallSession)) > 0) {
            return false;
        }
        
        return true;
    }
}
