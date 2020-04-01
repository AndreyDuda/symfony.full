<?php
declare(strict_types=1);

namespace App\Model\User\UserCase\SignUp\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Lenght(min=6)
     */
    public $password;

}