<?php
declare(strict_types=1);

namespace App\Security;


use App\ReadModel\User\AuthView;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $users;

    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
    }

    public function loadUserByUsername($userName): UserInterface
    {
        $user = $this->loadUser($userName);
        return self::identityByUser($user);
    }

    public function refreshUser(UserInterface $identity): UserInterface
    {
        if (!$identity instanceof UserIdentity) {
            throw new UnsupportedUserException('Invalid user class' . \get_class($identity));
        }

        $user = $this->loadUser($identity->getUserName());
        return self::identityByUser($user);
    }

    public function supportsClass($class): bool
    {
        return $class instanceof UserIdentity;
    }

    public function loadUser($userName): AuthView
    {
        if (!$user = $this->users->findForAuth($userName)) {
            throw new UsernameNotFoundException('');
        }

        return $user;
    }

    public static function identityByUser(AuthView $user): UserIdentity
    {
        return new UserIdentity(
            $user->id,
            $user->email,
            $user->password_hash,
            $user->role,
            $user->status
        );
    }
}