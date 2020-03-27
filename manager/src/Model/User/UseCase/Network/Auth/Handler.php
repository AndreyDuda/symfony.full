<?php
declare(strict_types=1);

namespace App\Model\User\UserCase\Network\Auth;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\Flusher;

class Handler
{
    /** @var UserRepository  */
    private $user;
    /** @var Flusher  */
    private $flusher;

    public function __construct(UserRepository $user, Flusher $flusher)
    {
        $this->user = $user;
        $this->flusher = $flusher;
    }

    public function handler(Command $command): void
    {
        if ($this->user->hasByNetworkIdentity($command->network, $command->identity)) {
            throw new \DomainException('User already exists.');
        }

        $user = new User(
            Id::next(),
            new \DateTimeImmutable()
        );

        $user->signUpNetWork(
            $command->network,
            $command->identity
        );

        $this->user->add($user);
        $this->flusher->flush();


    }
}