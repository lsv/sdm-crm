<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ContactPhoneNumberRepository")
 */
class ContactPhoneNumber
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Contact
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="phonenumbers")
     * @ApiSubresource()
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", unique=true)
     */
    private $number;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * Get Id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get Contact.
     *
     * @return Contact
     */
    public function getContact(): Contact
    {
        return $this->contact;
    }

    /**
     * Set Contact.
     *
     * @param Contact $contact
     *
     * @return ContactPhoneNumber
     */
    public function setContact(Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get Number.
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Set Number.
     *
     * @param string $number
     *
     * @return ContactPhoneNumber
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get Name.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set Name.
     *
     * @param null|string $name
     *
     * @return ContactPhoneNumber
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
