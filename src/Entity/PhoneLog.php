<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneLogRepository")
 * @ORM\Table(name="phone_log", indexes={
 *  @ORM\Index(name="uuid", columns={"uuid"}),
 * })
 */
class PhoneLog
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
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", unique=true)
     */
    private $uuid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="call_date", type="datetime", nullable=true)
     */
    private $callDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="from_number", type="string", nullable=true)
     */
    private $fromNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="to_number", type="string", nullable=true)
     */
    private $toNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direction", type="string", nullable=true)
     */
    private $direction;

    /**
     * @var PhoneLogStatus[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\PhoneLogStatus", mappedBy="phoneLog", cascade={"persist"})
     * @ORM\OrderBy({"date" = "desc"})
     */
    private $statuses;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="from_number_hidden", type="boolean", nullable=true)
     */
    private $fromNumberHidden;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="answer_date", type="datetime", nullable=true)
     */
    private $answerDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="hangup_type", type="string", nullable=true)
     */
    private $hangupType;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="hangup_date", type="datetime", nullable=true)
     */
    private $hangupDate;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->statuses = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set uuid.
     *
     * @param string $uuid
     *
     * @return PhoneLog
     */
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid.
     *
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Set callDate.
     *
     * @param \DateTime $callDate
     *
     * @return PhoneLog
     */
    public function setCallDate(\DateTime $callDate): self
    {
        $this->callDate = $callDate;

        return $this;
    }

    /**
     * Get callDate.
     *
     * @return \DateTime
     */
    public function getCallDate(): ?\DateTime
    {
        return $this->callDate;
    }

    /**
     * Set fromNumber.
     *
     * @param string|null $fromNumber
     *
     * @return PhoneLog
     */
    public function setFromNumber(string $fromNumber = null): self
    {
        $this->fromNumber = $fromNumber;

        return $this;
    }

    /**
     * Get fromNumber.
     *
     * @return string|null
     */
    public function getFromNumber(): ?string
    {
        return $this->fromNumber;
    }

    /**
     * Set toNumber.
     *
     * @param string|null $toNumber
     *
     * @return PhoneLog
     */
    public function setToNumber(string $toNumber = null): self
    {
        $this->toNumber = $toNumber;

        return $this;
    }

    /**
     * Get toNumber.
     *
     * @return string|null
     */
    public function getToNumber(): ?string
    {
        return $this->toNumber;
    }

    /**
     * Set direction.
     *
     * @param string|null $direction
     *
     * @return PhoneLog
     */
    public function setDirection(string $direction = null): self
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction.
     *
     * @return string|null
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * Set fromNumberHidden.
     *
     * @param bool|null $fromNumberHidden
     *
     * @return PhoneLog
     */
    public function setFromNumberHidden(bool $fromNumberHidden = null): self
    {
        $this->fromNumberHidden = $fromNumberHidden;

        return $this;
    }

    /**
     * Get fromNumberHidden.
     *
     * @return bool|null
     */
    public function getFromNumberHidden(): ?bool
    {
        return $this->fromNumberHidden;
    }

    /**
     * Set answerDate.
     *
     * @param \DateTime|null $answerDate
     *
     * @return PhoneLog
     */
    public function setAnswerDate(\DateTime $answerDate = null): self
    {
        $this->answerDate = $answerDate;

        return $this;
    }

    /**
     * Get answerDate.
     *
     * @return \DateTime|null
     */
    public function getAnswerDate(): ?\DateTime
    {
        return $this->answerDate;
    }

    /**
     * Set hangupType.
     *
     * @param string|null $hangupType
     *
     * @return PhoneLog
     */
    public function setHangupType(string $hangupType = null): self
    {
        $this->hangupType = $hangupType;

        return $this;
    }

    /**
     * Get hangupType.
     *
     * @return string|null
     */
    public function getHangupType(): ?string
    {
        return $this->hangupType;
    }

    /**
     * Set hangupDate.
     *
     * @param \DateTime|null $hangupDate
     *
     * @return PhoneLog
     */
    public function setHangupDate(\DateTime $hangupDate = null): self
    {
        $this->hangupDate = $hangupDate;

        return $this;
    }

    /**
     * Get hangupDate.
     *
     * @return \DateTime|null
     */
    public function getHangupDate(): ?\DateTime
    {
        return $this->hangupDate;
    }

    /**
     * Set statusses.
     *
     * @param iterable|null $statuses
     *
     * @return PhoneLog
     */
    public function setStatuses(iterable $statuses = null): self
    {
        $this->statuses = new ArrayCollection();
        if (is_iterable($statuses)) {
            foreach ($statuses as $status) {
                $this->addStatus($status);
            }
        }

        return $this;
    }

    /**
     * Add statuss.
     *
     * @param PhoneLogStatus $status
     *
     * @return PhoneLog
     */
    public function addStatus(PhoneLogStatus $status): self
    {
        $status->setPhoneLog($this);
        $this->statuses->add($status);

        return $this;
    }

    /**
     * Remove statuss.
     *
     * @param PhoneLogStatus $status
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeStatus(PhoneLogStatus $status): bool
    {
        return $this->statuses->removeElement($status);
    }

    /**
     * Get statusses.
     *
     * @return ArrayCollection|PhoneLogStatus[]
     */
    public function getStatuses()
    {
        return $this->statuses;
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
     * @return PhoneLog
     */
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
