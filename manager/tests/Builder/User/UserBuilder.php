<?php
declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;

class UserBuilder
{
    private $id;
    private $date;

    private $email;
    private $hash;
    private $token;
    private $confirmed;

    private $network;
    private $identity;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new \DateTimeImmutable();
    }

    public function confirmed():self
    {
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email('test@test.test');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';
        return $clone;

    }

    public function viaNetwork(string $network = null, string $identity = null): self
    {
        $clone = clone $this;
        $this->network = $network ?? 'vk';
        $clone->identity = $identity ?? '001';
        return $clone;
    }

}