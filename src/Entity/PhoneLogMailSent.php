<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneLogMailSentRepository")
 * @ORM\Table(name="phone_log_mailsent", indexes={
 *  @ORM\Index(name="number", columns={"number"}),
 * })
 */
class PhoneLogMailSent
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string")
     */
    private $number;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Get Id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return PhoneLogMailSent
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }
}
