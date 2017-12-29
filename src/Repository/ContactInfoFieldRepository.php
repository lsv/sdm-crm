<?php

namespace App\Repository;

use App\Entity\ContactInfoField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ContactInfoFieldRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactInfoField::class);
    }

    /**
     * @return ContactInfoField[]|array
     */
    public function findRequiredFields(): array
    {
        $qb = $this->createQueryBuilder('contact_info_field');
        $qb->andWhere($qb->expr()->eq('contact_info_field.required', 1));
        $qb->orderBy('contact_info_field.sorting', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return ContactInfoField[]|array
     */
    public function findOptionalFields(): array
    {
        $qb = $this->createQueryBuilder('contact_info_field');
        $qb->andWhere($qb->expr()->eq('contact_info_field.required', 0));
        $qb->orderBy('contact_info_field.sorting', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
