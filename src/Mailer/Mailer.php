<?php

namespace App\Mailer;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $fromAddress;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @param \Swift_Mailer $mailer
     * @param string        $fromAddress
     * @param string        $fromName
     */
    public function __construct(\Swift_Mailer $mailer, $fromAddress, $fromName)
    {
        $this->mailer = $mailer;
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
    }

    /**
     * Get FromAddress.
     *
     * @return string
     */
    public function getStandardAddress(): string
    {
        return $this->fromAddress;
    }

    /**
     * Get FromName.
     *
     * @return string
     */
    public function getStandardName(): string
    {
        return $this->fromName;
    }

    /**
     * @param \Swift_Message $message
     *
     * @return int
     */
    public function send(\Swift_Message $message): int
    {
        if (!$message->getSender()) {
            $message->setSender($this->fromAddress, $this->fromName);
        }

        if (!$message->getTo()) {
            $message->setTo($this->fromAddress, $this->fromName);
        }

        return $this->mailer->send($message);
    }
}
