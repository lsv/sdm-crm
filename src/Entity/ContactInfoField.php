<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ContactInfoFieldRepository")
 * @ORM\Table(name="contact_info_field")
 */
class ContactInfoField
{
    public const TYPE_INTEGER = 'integer';
    public const TYPE_STRING = 'string';
    public const TYPE_DECIMAL = 'decimal';
    public const TYPE_DATE = 'date';
    public const TYPE_DATETIME = 'datetime';

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
     * @ORM\Column(name="name", type="string", unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="required", type="boolean")
     */
    private $required = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="meta", type="boolean")
     */
    private $meta = false;

    /**
     * @var int|null
     *
     * @ORM\Column(name="sorting", type="integer", nullable=true)
     */
    private $sorting = 999;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set Name.
     *
     * @param string $name
     *
     * @return ContactInfoField
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set Type.
     *
     * @param string $type
     *
     * @return ContactInfoField
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Required.
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Set Required.
     *
     * @param bool $required
     *
     * @return ContactInfoField
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get Sorting.
     *
     * @return int|null
     */
    public function getSorting(): ?int
    {
        return $this->sorting;
    }

    /**
     * Set Sorting.
     *
     * @param int $sorting
     *
     * @return ContactInfoField
     */
    public function setSorting(int $sorting): self
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * Get Meta.
     *
     * @return bool
     */
    public function isMeta(): bool
    {
        return $this->meta;
    }

    /**
     * Set Meta.
     *
     * @param bool $meta
     *
     * @return ContactInfoField
     */
    public function setMeta(bool $meta): self
    {
        $this->meta = $meta;

        return $this;
    }
}
