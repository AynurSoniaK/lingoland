<?php
namespace App\Security;

use App\Security\AccountDisabledException;
use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

            // L’utilisateur n’est pas activé par l’administrateur
            if (!$user->IsVerified()) {
                throw new CustomUserMessageAccountStatusException('Veuillez vérifier votre email avant de vous connecter');       
            }
    }

    public function checkPostAuth(UserInterface $user)
    {
        
    }
}