<?php
declare(strict_types=1);

namespace App\Model\User\UserCase\SignUp\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\Flusher;
use App\Model\User\Service\PasswordHasher;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;

class Handler
{
    private $users;
    private $hasher;
    private $flusher;

    public function __construct(UserRepository $user, PasswordHasher $hasher, Flusher $flusher)
    {
        $this->users = $user;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
    }

    public function handler(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exists.');
        }

        $user = new User(
            Id::next(),
            new \DateTimeImmutable(),
            $email,
            $this->hasher->hash($command->password)
        );

        $this->users->add($user);
    }
}