<?php

namespace App\Events;

use App\Entity\PhoneLog;
use Symfony\Component\EventDispatcher\Event;

class PhoneLogEvent extends Event
{
    public const NAME = 'app.phonelog.event';

    /**
     * @var PhoneLog
     */
    private $phoneLog;

    /**
     * @var \stdClass
     */
    private $rawLog;

    public function __construct(PhoneLog $phoneLog, \stdClass $rawLog)
    {
        $this->phoneLog = $phoneLog;
        $this->rawLog = $rawLog;
    }

    /**
     * Get PhoneLog.
     *
     * @return PhoneLog
     */
    public function getPhoneLog(): PhoneLog
    {
        return $this->phoneLog;
    }

    /**
     * Get RawLog.
     *
     * @return \stdClass
     */
    public function getRawLog(): \stdClass
    {
        return $this->rawLog;
    }
}
