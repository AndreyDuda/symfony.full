<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\AuthTest;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Network;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            Id::next(),
            new \DateTimeImmutable()
        );

        $user->signUpNetWork(
            $network = 'vk',
            $identity = '0000001'
        );

        self::assertTrue($user->isActive());

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertInstanceOf(Network::class, $first = reset($networks));
        self::assertEquals($network, $first->getNetwork());
    }

    public function testAlready(): void
    {
        $user = new User(
            $id = Id::next(),
            $date = new \DateTimeImmutable()
        );

        $user->signUpNetWork(
            $network = 'vk',
            $identity = '0000001'
        );

        $this->expectExceptionMessage('User is already signed up.');

        $user->signUpNetWork($network, $identity);
    }
}