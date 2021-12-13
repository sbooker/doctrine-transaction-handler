<?php

declare(strict_types=1);

namespace Sbooker\TransactionManager;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTransactionHandler implements TransactionHandler
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function begin(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    /**
     * @param array $entities
     * @throws \Exception
     */
    public function commit(array $entities): void
    {
        if (!$this->getEntityManager()->isOpen()) {
            throw new \Exception('Entity manager closed');
        }

        $this->getEntityManager()->getUnitOfWork()->computeChangeSets();
        $this->getEntityManager()->getUnitOfWork()->commit($entities);
        $this->getEntityManager()->commit();
        $this->clear();
    }

    public function rollback(): void
    {
        $this->clear();
        $this->getEntityManager()->rollback();
    }

    public function clear(): void
    {
        if (!$this->getEntityManager()->getConnection()->isTransactionActive()) {
            $this->getEntityManager()->clear();
        }
    }

    public function persist(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function getLocked(string $entityClassName, $entityId): ?object
    {
        return $this->getEntityManager()->getRepository($entityClassName)->find($entityId, LockMode::PESSIMISTIC_WRITE);
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }
}