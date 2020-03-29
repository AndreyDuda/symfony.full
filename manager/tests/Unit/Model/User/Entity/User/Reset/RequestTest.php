<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Reset;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testAlready(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = $this->buildSignedUpByEmailUser();
        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Resetting is already request');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $now = new \DateTimeImmutable();

        $user = $this->buildSignedUpByEmailUser();
        $token1 = new ResetToken('token', $now->modify('+1 day'));
        $user->requestPasswordReset($token1, $now);

        self::assertEquals($token1, $user->getResetToken());

        $token2 = new ResetToken('token', $now->modify('+3 day'));
        $user->requestPasswordReset($token2, $now->modify('+2 day'));

        self::assertEquals($token2, $user->getResetToken());
    }

    public function testWithoutEmail(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = User::signUpNetWork(
            $id = Id::next(),
            $date = new \DateTimeImmutable(), $network = 'vk',
            $identity = '0000001'
        );

        $this->expectExceptionMessage('Email is not specified');
        $user->requestPasswordReset($token, $now);
    }

    public function buildSignedUpByEmailUser(): User
    {
        $user = User::signUpByEmail(
            $id = Id::next(),
            $date = new \DateTimeImmutable(),
            $email = new Email('test@teat.test'),
            $hash = 'bash',
            $token = 'Token'
        );

        return $user;
    }
}