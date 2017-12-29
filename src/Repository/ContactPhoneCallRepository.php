<?php

namespace App\Repository;

use App\Entity\ContactPhoneCall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ContactPhoneCallRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactPhoneCall::class);
    }

    /**
     * @param string $uuid
     *
     * @return ContactPhoneCall|null
     */
    public function findByUuid($uuid): ?ContactPhoneCall
    {
        $qb = $this->createQueryBuilder('contactPhoneCall');
        $qb->andWhere($qb->expr()->eq('contactPhoneCall.uuid', ':uuid'));
        $qb->setParameter(':uuid', $uuid);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
