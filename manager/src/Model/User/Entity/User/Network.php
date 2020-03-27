<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Ramsey\Uuid\Uuid;

class Network
{
    /** @var string  */
    private $id;
    /** @var User  */
    private $user;
    /** @var string  */
    private $network;
    /** @var string  */
    private $identity;

    public function __construct(User $user, string $network, string $identity)
    {
        $this->id = Uuid::uuid4();
        $this->user = $user;
        $this->network = $network;
        $this->identity = $identity;
    }

    public function isForNetwork(string $network): bool
    {
        return $this->network == $network;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function getIdentuty(): string
    {
        return $this->identity;
    }
}