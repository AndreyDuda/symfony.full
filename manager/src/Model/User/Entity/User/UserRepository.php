<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use App\Model\User\UserCase\Network\Auth\Command;

Interface UserRepository
{
    public function hasByNetworkIdentity(string $network, string $identity): ?User;

    public function findByConfirmToken(string $token): ?User;

    public function hasByEmail(Email $email): bool;

    public function add(User $user): void;
}