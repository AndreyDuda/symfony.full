<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_user_networks", uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"network", "identity"})
 *     })
 */
class Network
{
    /**
     * @var string
     * @ORM\Column(type="guid")
     * @ORM\Id)
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="networks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string", name="network" ,length=32, nullable=true)
     */
    private $network;

    /**
     * @var string
     * @ORM\Column(type="string", name="identity", nullable=true)
     */
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