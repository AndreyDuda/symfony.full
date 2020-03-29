<?php
declare(strict_types=1);

namespace App\Model\User\UserCase\Role;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\Flusher;

class Handler
{
    private $users;
    private $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handler(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));
        $user->changeRole(new Role($command->role));
        $this->flusher->flush();
    }
}