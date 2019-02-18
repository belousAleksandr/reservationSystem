<?php

namespace App\Security\Voter;

use App\Entity\Hall;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class HallVoter extends Voter
{
    /** @var AccessDecisionManagerInterface */
    private $decisionManager;

    const DELETE = 'ROLE_ADMIN_HALL_DELETE';

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        return \in_array($attribute, [self::DELETE])
            && $subject instanceof Hall;
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

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($subject, $token);
        }

        return false;
    }

    /**
     * Checks if particular Hall can be deleted
     *
     * @param Hall $hall
     * @param TokenInterface $token
     * @return bool
     */
    private function canDelete(Hall $hall, TokenInterface $token): bool
    {
        if (!$this->mayHallSessionsBeDeleted($hall, $token)) {
            return false;
        }

        return true;
    }

    /**
     * Checks if HallSessions may be deleted or not
     *
     * @param Hall $hall
     * @param TokenInterface $token
     * @return bool
     */
    private function mayHallSessionsBeDeleted(Hall $hall, TokenInterface $token): bool
    {
        foreach ($hall->getHallSessions() as $hallSession) {
            if (!$this->decisionManager->decide($token, [HallSessionVoter::DELETE], $hallSession)) {
                return false;
            }
        }

        return true;
    }
}
