<?php
declare(strict_types=1);

namespace App\Model\User\UserCase\Reset\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\Flusher;
use App\Model\User\Service\ResetTokenizer;
use App\Model\User\Service\ResetTokenSender;

class Handler
{
    /** @var UserRepository  */
    private $user;
    /** @var ResetTokenizer  */
    private $tokenizer;
    /** @var Flusher  */
    private $flusher;
    /** @var ResetTokenSender  */
    private $sender;

    public function __construct(
        UserRepository $user,
        ResetTokenizer $tokenizer,
        Flusher $flusher,
        ResetTokenSender $sender
    )
    {
        $this->user = $user;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
        $this->sender = $sender;
    }

    public function handler(Command $command): void
    {
        $user = $this->user->getByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->tokenizer->generate(),
            new \DateTimeImmutable()
        );

        $this->flusher->flush();

        $this->sender->send($user->getEmail(), $user->getResetToken());
    }
}