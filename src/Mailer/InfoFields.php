<?php

namespace App\Mailer;

use App\Entity\ContactInfoField;
use Doctrine\Common\Persistence\ManagerRegistry;

class InfoFields
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return array
     */
    public function getContactFields(): array
    {
        return [
            'name' => [
                'required' => true,
            ],
            'companyName' => [
                'required' => false,
            ],
            'webpage' => [
                'required' => false,
            ],
        ];
    }

    /**
     * @return array|ContactInfoField[]
     */
    public function getRequiredInfoFields(): array
    {
        $repo = $this->registry->getRepository(ContactInfoField::class);

        return $repo->findRequiredFields();
    }

    /**
     * @return array|ContactInfoField[]
     */
    public function getOptionalInfoFields(): array
    {
        $repo = $this->registry->getRepository(ContactInfoField::class);

        return $repo->findOptionalFields();
    }
}
