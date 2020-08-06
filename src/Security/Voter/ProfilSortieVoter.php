<?php

namespace App\Security\Voter;

use App\Entity\ProfilSortie;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProfilSortieVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'VIEW','ADD','DELETE'])
            && $subject instanceof \App\Entity\ProfilSortie;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        
      /** @var ProfilSortie $subject */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
               return $user->getRoles()[0]==="ROLE_ADMIN";
                break;
            case 'VIEW':
                return $user->getRoles()[0]==="ROLE_ADMIN";
             
                break;
            case 'ADD':
                return $user->getRoles()[0]==="ROLE_ADMIN";
                break;
            case 'DELETE':
                return $user->getRoles()[0]==="ROLE_ADMIN";
                break;
            
        }
        
        return false;
    }
}
