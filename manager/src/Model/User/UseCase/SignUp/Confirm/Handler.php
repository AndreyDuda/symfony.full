<?php
declare(strict_types=1);

namespace App\Model\User\UserCase\SignUp\Confirm;

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
        if (!$user = $this->user->findByConfirmToken($command->token)) {
            throw new \DomainException('Incorrect or confirmed token.');
        }

        $user->confirmSingUp();
        $this->flusher->flush();
    }
}