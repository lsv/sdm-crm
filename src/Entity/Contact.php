<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 * @ORM\Table(name="contact")
 */
class Contact
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="company_name", type="string", nullable=true)
     */
    private $companyName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="webpage", type="string", nullable=true)
     */
    private $webpage;

    /**
     * @var ContactPhoneNumber[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ContactPhoneNumber", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $phonenumbers;

    /**
     * @var ContactInfo[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ContactInfo", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $infos;

    /**
     * @var ContactNote[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ContactNote", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $notes;

    /**
     * @var ContactPhoneCall[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ContactPhoneCall", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $phonecalls;

    public function __construct()
    {
        $this->phonenumbers = new ArrayCollection();
        $this->infos = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->phonecalls = new ArrayCollection();
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
     * Get Name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set Name.
     *
     * @param string $name
     *
     * @return Contact
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get CompanyName.
     *
     * @return null|string
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * Set CompanyName.
     *
     * @param null|string $companyName
     *
     * @return Contact
     */
    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get Webpage.
     *
     * @return null|string
     */
    public function getWebpage(): ?string
    {
        return $this->webpage;
    }

    /**
     * Set Webpage.
     *
     * @param null|string $webpage
     *
     * @return Contact
     */
    public function setWebpage(?string $webpage): self
    {
        $this->webpage = $webpage;

        return $this;
    }

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    public function hasField($fieldName): bool
    {
        foreach ($this->getInfos() as $info) {
            if ($info && $info->getField()->getName() === $fieldName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $fieldName
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function getValueFromField($fieldName)
    {
        foreach ($this->infos as $field) {
            if ($field->getField()->getName() === $fieldName) {
                return $field->getValue();
            }
        }

        throw new \InvalidArgumentException('Field with name "'.$fieldName.'" does not exists');
    }

    /**
     * Get Fields.
     *
     * @return ContactInfo[]|ArrayCollection
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * Set Fields.
     *
     * @param ContactInfo[]|ArrayCollection|null|iterable $infos
     *
     * @return Contact
     */
    public function setInfos(iterable $infos = null): self
    {
        $this->infos = new ArrayCollection();
        if (is_iterable($infos)) {
            foreach ($infos as $field) {
                $this->addInfo($field);
            }
        }

        return $this;
    }

    /**
     * @param ContactInfo $field
     *
     * @return Contact
     */
    public function addInfo(ContactInfo $field): self
    {
        $field->setContact($this);
        $this->infos->add($field);

        return $this;
    }

    /**
     * Get Notes.
     *
     * @return ContactNote[]|ArrayCollection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set Notes.
     *
     * @param ContactNote[]|ArrayCollection|iterable|null $notes
     *
     * @return Contact
     */
    public function setNotes(iterable $notes = null): self
    {
        $this->notes = new ArrayCollection();
        if (is_iterable($notes)) {
            foreach ($notes as $note) {
                $this->addNote($note);
            }
        }

        return $this;
    }

    /**
     * @param ContactNote $note
     *
     * @return Contact
     */
    public function addNote(ContactNote $note): self
    {
        $note->setContact($this);
        $this->notes->add($note);

        return $this;
    }

    /**
     * Get Phonecalls.
     *
     * @return ContactPhoneCall[]|ArrayCollection
     */
    public function getPhonecalls()
    {
        return $this->phonecalls;
    }

    /**
     * Set Phonecalls.
     *
     * @param ContactPhoneCall[]|ArrayCollection|iterable|null $phonecalls
     *
     * @return Contact
     */
    public function setPhonecalls(iterable $phonecalls = null): self
    {
        $this->phonecalls = new ArrayCollection();
        if (is_iterable($phonecalls)) {
            foreach ($phonecalls as $call) {
                $this->addPhonecall($call);
            }
        }

        return $this;
    }

    /**
     * @param ContactPhoneCall $phonecall
     *
     * @return Contact
     */
    public function addPhonecall(ContactPhoneCall $phonecall): self
    {
        $phonecall->setContact($this);
        $this->phonecalls->add($phonecall);

        return $this;
    }

    /**
     * Get Phonenumbers.
     *
     * @return ContactPhoneNumber[]|ArrayCollection
     */
    public function getPhonenumbers()
    {
        return $this->phonenumbers;
    }

    /**
     * Set Phonenumbers.
     *
     * @param ContactPhoneNumber[]|ArrayCollection|iterable|null $phoneNumbers
     *
     * @return Contact
     */
    public function setPhonenumbers(iterable $phoneNumbers = null): self
    {
        $this->phonenumbers = new ArrayCollection();
        if (is_iterable($phoneNumbers)) {
            foreach ($phoneNumbers as $number) {
                $this->addPhonenumber($number);
            }
        }

        return $this;
    }

    /**
     * Add phonenumber.
     *
     * @param ContactPhoneNumber $phonenumber
     *
     * @return Contact
     */
    public function addPhonenumber(ContactPhoneNumber $phonenumber): self
    {
        $phonenumber->setContact($this);
        $this->phonenumbers->add($phonenumber);

        return $this;
    }
}
