<?php

namespace App\Repository;

use App\Entity\PhoneLogMailSent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PhoneLogMailSentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhoneLogMailSent::class);
    }

    /**
     * @param string $number
     * @param int    $hour
     *
     * @return int
     */
    public function findSentByNumber(string $number, int $hour): int
    {
        $qb = $this->createQueryBuilder('phone_log_mail_sent');
        $qb->select($qb->expr()->count('phone_log_mail_sent.id'));
        $qb->andWhere(
            $qb->expr()->eq('phone_log_mail_sent.number', ':number'),
            $qb->expr()->gte('phone_log_mail_sent.createdAt', ':date')
        );
        $qb->setParameter(':number', $number);
        $qb->setParameter(':date', new \DateTime('-'.$hour.' hour'));

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
