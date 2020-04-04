<?php
declare(strict_types=1);

namespace App\Model\User\UserCase\Reset\Reset;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $token;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $password;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}