<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;

class UserRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Doctrine\Persistence\ObjectRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    public function hasByNetworkIdentity(string $network, string $identity): bool
    {
        /** @var QueryBuilder $qb */
        $qb = $this->repo->createQueryBuilder('t');
        return $qb
            ->select('COUNT(t.id)')
            ->innerJoin('t.network', 'n')
            ->andWhere('n.network = :network and n.identity = :identity')
            ->setParameter(':network', $network)
            ->setParameter(':identity', $identity)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param string $token
     * @return User|Object|null
     */
    public function findByConfirmToken(string $token): ?User
    {
        return $this->repo->findOneBy(['confirmToken' => $token]);
    }

    /**
     * @param string $token
     * @return User|Object|null
     */
    public function findByResetToken(string $token): ?User
    {
        return $this->repo->findOneBy(['resetToken.token' => $token]);
    }

    public function hasByEmail(Email $email): bool
    {
        /** @var QueryBuilder $qb */
        $qb = $this->repo->createQueryBuilder('t');
        return $qb
            ->select('COUNT(t.id)')
            ->andWhere('t.email = :email')
            ->setParameter(':email', $email->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function getByEmail(Email $email): User
    {
        /** @var User $user */
        if (!$user = $this->repo->findOneBy(['email' => $email->getValue()])) {
            throw new EntityNotFoundException('User is not found');
        }
        return $user;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function get(Id $id): User
    {
        /** @var User $user */
        if (!$user = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('User is not found.');
        }
        return $user;
    }
}