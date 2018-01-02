<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ContactInfoRepository")
 * @ORM\Table(name="contact_info")
 */
class ContactInfo
{
    private static $fieldTypes = [
        ContactInfoField::TYPE_STRING => ['setter' => 'setString', 'getter' => 'getString'],
        ContactInfoField::TYPE_DATE => ['setter' => 'setDate', 'getter' => 'getDate'],
        ContactInfoField::TYPE_DATETIME => ['setter' => 'setDateTime', 'getter' => 'getDateTime'],
        ContactInfoField::TYPE_INTEGER => ['setter' => 'setInteger', 'getter' => 'getInteger'],
        ContactInfoField::TYPE_DECIMAL => ['setter' => 'setDecimal', 'getter' => 'getDecimal'],
    ];

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var Contact
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="infos")
     */
    private $contact;

    /**
     * @var ContactInfoField
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ContactInfoField", cascade={"persist"})
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     */
    private $field;

    /**
     * @var int|null
     *
     * @ORM\Column(name="integer_value", type="integer", nullable=true)
     */
    private $integer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="string_value", type="string", nullable=true)
     */
    private $string;

    /**
     * @var float|null
     *
     * @ORM\Column(name="decimal_value", type="float", nullable=true)
     */
    private $decimal;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_value", type="date", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datetime_value", type="datetime", nullable=true)
     */
    private $datetime;

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
     * @param ContactInfoField $field
     * @param mixed            $value
     *
     * @throws \InvalidArgumentException
     *
     * @return ContactInfo
     */
    public function setValueByType(ContactInfoField $field, $value): self
    {
        if (isset(self::$fieldTypes[$field->getType()])) {
            $setter = self::$fieldTypes[$field->getType()]['setter'];
            $this->setField($field);

            return $this->{$setter}($value);
        }

        throw new \InvalidArgumentException('Field type "'.$field->getType().'" does not have a setter');
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function getValue()
    {
        if (isset(self::$fieldTypes[$this->getField()->getType()])) {
            $getter = self::$fieldTypes[$this->getField()->getType()]['getter'];

            return $this->{$getter}();
        }

        throw new \InvalidArgumentException('Field type "'.$this->getField()->getType().'" does not have a getter');
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
     * @return ContactInfo
     */
    public function setContact(Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get Field.
     *
     * @return ContactInfoField
     */
    public function getField(): ContactInfoField
    {
        return $this->field;
    }

    /**
     * Set Field.
     *
     * @param ContactInfoField $field
     *
     * @return ContactInfo
     */
    public function setField(ContactInfoField $field): self
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get Integer.
     *
     * @return int|null
     */
    public function getInteger(): ?int
    {
        return $this->integer;
    }

    /**
     * Set Integer.
     *
     * @param int $integer
     *
     * @return ContactInfo
     */
    public function setInteger(int $integer): self
    {
        $this->integer = $integer;

        return $this;
    }

    /**
     * Get String.
     *
     * @return string|null
     */
    public function getString(): ?string
    {
        return $this->string;
    }

    /**
     * Set String.
     *
     * @param string $string
     *
     * @return ContactInfo
     */
    public function setString(string $string): self
    {
        $this->string = $string;

        return $this;
    }

    /**
     * Get Decimal.
     *
     * @return float|null
     */
    public function getDecimal(): ?float
    {
        return $this->decimal;
    }

    /**
     * Set Decimal.
     *
     * @param float $decimal
     *
     * @return ContactInfo
     */
    public function setDecimal(float $decimal): self
    {
        $this->decimal = $decimal;

        return $this;
    }

    /**
     * Get Date.
     *
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * Set Date.
     *
     * @param \DateTime $date
     *
     * @return ContactInfo
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get Datetime.
     *
     * @return \DateTime|null
     */
    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    /**
     * Set Datetime.
     *
     * @param \DateTime $datetime
     *
     * @return ContactInfo
     */
    public function setDatetime(\DateTime $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }
}
