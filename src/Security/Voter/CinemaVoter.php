<?php

namespace App\Security\Voter;

use App\Entity\Cinema;
use App\Repository\HallRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CinemaVoter extends Voter
{
    /** @var AccessDecisionManagerInterface */
    private $decisionManager;

    /** @var HallRepository */
    private $hallRepository;

    const DELETE = 'ROLE_ADMIN_CINEMA_DELETE';

    /**
     * CinemaVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     * @param HallRepository $hallRepository
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager, HallRepository $hallRepository)
    {
        $this->decisionManager = $decisionManager;
        $this->hallRepository = $hallRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        return \in_array($attribute, [self::DELETE])
            && $subject instanceof Cinema;
    }

    /**
     * {@inheritdoc}
     */
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
                return $this->canDelete($subject, $token);
        }

        return false;
    }

    /**
     * Checks if particular Cinema can be deleted
     *
     * @param Cinema $cinema
     * @param TokenInterface $token
     * @return bool
     */
    private function canDelete(Cinema $cinema, TokenInterface $token): bool
    {
        if (!$this->mayHallsBeDeleted($cinema, $token)) {
            return false;
        }

        return true;
    }

    /**
     * Checks if Halls may be deleted or not
     *
     * @param Cinema $cinema
     * @param TokenInterface $token
     * @return bool
     */
    private function mayHallsBeDeleted(Cinema $cinema, TokenInterface $token): bool
    {
        foreach ($this->hallRepository->findBy(['cinema' => $cinema]) as $hall) {
            if (!$this->decisionManager->decide($token, [HallVoter::DELETE], $hall)) {
                return false;
            }
        }

        return true;
    }
}
