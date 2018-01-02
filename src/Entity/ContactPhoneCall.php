<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ContactPhoneCallRepository")
 * @ORM\Table(name="contact_phone_call")
 */
class ContactPhoneCall
{
    use TimestampableEntity;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", unique=true)
     */
    private $uuid;

    /**
     * @var Contact
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="phonecalls")
     */
    private $contact;

    /**
     * Duration in seconds.
     *
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

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
     * Get Uuid.
     *
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Set Uuid.
     *
     * @param string $uuid
     *
     * @return ContactPhoneCall
     */
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
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
     * @return ContactPhoneCall
     */
    public function setContact(Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get Duration.
     *
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * Set Duration.
     *
     * @param int $duration
     *
     * @return ContactPhoneCall
     */
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
