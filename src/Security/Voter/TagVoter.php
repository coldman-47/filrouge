<?php

namespace App\Security\Voter;

use App\Entity\Tag;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TagVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'VIEW','EDIT','DELETE'])
            && $subject instanceof \App\Entity\Tag;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        
      /** @var Tag $subject */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                if($user->getRoles()[0]==="ROLE_ADMIN" || $user->getRoles()[0]==="ROLE_FORMATEUR"){
                    return true;
                }
                break;
            case 'VIEW':
                if($user->getRoles()[0]==="ROLE_ADMIN" || $user->getRoles()[0]==="ROLE_FORMATEUR"){
                    return true;
                }
                break;
            case 'ADD':
                if($user->getRoles()[0]==="ROLE_ADMIN" || $user->getRoles()[0]==="ROLE_FORMATEUR"){
                    return true;
                }
                break;
            case 'DELETE':
                return $user->getRoles()[0]==="ROLE_ADMIN";
                break;
            
        }
        return false;
    }
}
