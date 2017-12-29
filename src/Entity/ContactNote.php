<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactNoteRepository")
 * @ORM\Table(name="contact_note")
 */
class ContactNote
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
     * @var Contact
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="notes")
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;

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
     * @return ContactNote
     */
    public function setContact(Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get Note.
     *
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * Set Note.
     *
     * @param string $note
     *
     * @return ContactNote
     */
    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }
}
