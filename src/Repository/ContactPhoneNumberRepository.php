<?php

namespace App\Repository;

use App\Entity\ContactPhoneNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ContactPhoneNumberRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactPhoneNumber::class);
    }

    /**
     * @param string $number
     *
     * @return ContactPhoneNumber|null
     */
    public function findByNumber($number): ?ContactPhoneNumber
    {
        $qb = $this->createQueryBuilder('cpn');
        $qb->andWhere($qb->expr()->eq('cpn.number', ':number'));
        $qb->setParameter(':number', $number);
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
