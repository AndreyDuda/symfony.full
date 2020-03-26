<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    /** @var Id */
    private $id;
    /** @var \DateTimeImmutable  */
    private $date;
    /** @var Email  */
    private $email;
    /** @var string  */
    private $passwordHash;
    /** @var string|null  */
    private $confirmToken;
    /** @var string  */
    private $status;
    /** @var string */
    private $network;
    /** @var string */
    private $indentity;

    public static function signUpByEmail(
        Id $id,
        \DateTimeImmutable $date,
        Email $email,
        string $hash,
        string $token
    ): User
    {
        $user = new self();
        $user->id = $id;
        $user->date = $date;
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status = self::STATUS_WAIT;

        return $user;
    }

    public static function signUpNetWork(
        Id $id,
        \DateTimeImmutable $date,
        string $network,
        string $indentity
    ): User
    {
        $user = new self();
        $user->id = $id;
        $user->date = $date;
        $user->network = $network;
        $user->indentity = $indentity;
        $user->status = self::STATUS_WAIT;

        return $user;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function confirmSingUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function getId(): Id
    {
       return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }
}