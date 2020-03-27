<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';
    private const STATUS_NEW = NULL;

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
    private $networks;
    /** @var Network[]|ArrayCollection */
    private $indentity;

    public function __construct(Id $id, \DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->date = $date;
        $this->status = self::STATUS_NEW;
        $this->networks = new ArrayCollection();
    }

    public function signUpByEmail(
        Email $email,
        string $hash,
        string $token
    ): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already signed up.');
        }
        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
    }

    public function signUpNetWork(
        string $network,
        string $indentity
    ): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already signed up.');
        }
        $this->network = $network;
        $this->indentity = $indentity;
        $this->attachNetwork($network, $indentity);
        $this->status = self::STATUS_WAIT;
    }

    private function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForeNetwork($network)) {
                throw new \DomainException('Network is already attached');
            }
        }
        $this->networks->add(new Network($this, $network, $identity));
    }

    public function isNew(): bool
    {
        return $this->status == self::STATUS_NEW;
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