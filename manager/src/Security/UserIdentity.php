<?php
declare(strict_types=1);

namespace App\Security;

use App\Model\User\Entity\User\User;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentity implements UserInterface, EquatableInterface
{
    private $id;
    private $username;
    private $password;
    private $role;
    private $status;

    public function __construct(
        string $id,
        string $username,
        string $password,
        string $role,
        string $status
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->password =$password;
        $this->role = $role;
        $this->status = $status;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->status === User::STATUS_ACTIVE;
    }

    public function getUserName(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): string
    {
        return $this->role;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        return
            $this->id === $user->id &&
            $this->username === $user->getUserName() &&
            $this->password === $user->getPassword() &&
            $this->role === $user->getRoles() &&
            $this->status === $user->getStatus();
    }
}