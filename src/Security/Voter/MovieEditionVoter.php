<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieEditionVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === 'EDIT'
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        // Defensive : if the user is anonymous, do not grant access
        if (!$currentUser instanceof User && $subject instanceof Movie) {
            return false;
        }

        if($subject->getCreatedBy() === null || $subject->getCreatedBy()->isEqualTo($currentUser)){
            return true;
        }

        return false;
    }
}
