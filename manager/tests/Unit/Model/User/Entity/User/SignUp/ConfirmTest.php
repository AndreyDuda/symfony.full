<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccees(): void
    {
        $user = $this->buildSignedUpUser();

        $user->confirmSingUp();

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = $this->buildSignedUpUser();

        $user->confirmSingUp();
        $this->expectExceptionMessage('User is already confirmed.');
        $user->confirmSingUp();
    }

    public function buildSignedUpUser(): User
    {
        return new User(
            $id = Id::next(),
            $data = new \DateTimeImmutable(),
            $email = new Email('test@test.test'),
            $hash = 'hash',
            $token = 'token'
        );
    }
}