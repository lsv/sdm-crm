<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="phone_log_status")
 */
class PhoneLogStatus
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
     * @var PhoneLog|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PhoneLog", inversedBy="statuses")
     */
    private $phoneLog;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;

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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return PhoneLogStatus
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return PhoneLogStatus
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set phoneLog.
     *
     * @param PhoneLog|null $phoneLog
     *
     * @return PhoneLogStatus
     */
    public function setPhoneLog(PhoneLog $phoneLog = null): self
    {
        $this->phoneLog = $phoneLog;

        return $this;
    }

    /**
     * Get phoneLog.
     *
     * @return PhoneLog|null
     */
    public function getPhoneLog(): ?PhoneLog
    {
        return $this->phoneLog;
    }
}
