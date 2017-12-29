<?php

namespace App\Subscriber;

use App\Entity\Contact;
use App\Entity\ContactPhoneCall;
use App\Entity\ContactPhoneNumber;
use App\Entity\PhoneLog;
use App\Entity\PhoneLogMailSent;
use App\Entity\PhoneLogStatus;
use App\Events\PhoneLogEvent;
use App\Mailer\NewContactMailer;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PhoneLogSubscriber implements EventSubscriberInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var NewContactMailer
     */
    private $mailer;

    public function __construct(ManagerRegistry $registry, NewContactMailer $mailer)
    {
        $this->registry = $registry;
        $this->mailer = $mailer;
    }

    public function setPhoneLog(PhoneLogEvent $event)
    {
        $log = $event->getPhoneLog();
        $json = $event->getRawLog();
        if (!empty($json->data->from_number)) {
            $log->setFromNumber($json->data->from_number);
        }

        if (!empty($json->data->to_number)) {
            $log->setToNumber($json->data->to_number);
        }

        if (!empty($json->data->direction)) {
            $log->setDirection($json->data->direction);
        }

        if (!empty($json->data->from_number_hidden)) {
            $log->setFromNumberHidden(filter_var($json->data->from_number_hidden, FILTER_VALIDATE_BOOLEAN));
        }

        if (isset($json->data->answered_at)) {
            $log->setAnswerDate(new \DateTime($json->data->answered_at));
        }

        if (!empty($json->data->hangup_cause)) {
            $status = new PhoneLogStatus();
            $status->setDate(new \DateTime($json->timestamp));
            $status->setStatus('hangup');
            $log->addStatus($status);

            $log->setHangupType($json->data->hangup_cause);
            $log->setHangupDate(new \DateTime($json->timestamp));
        }

        if (!empty($json->data->status)) {
            $status = new PhoneLogStatus();
            $status->setDate(new \DateTime($json->timestamp));
            $status->setStatus($json->data->status);
            $log->addStatus($status);
        }

        if (!empty($json->data->duration)) {
            $log->setDuration((int) $json->data->duration);
        }

        if ($em = $this->registry->getManagerForClass(PhoneLog::class)) {
            $em->persist($log);
            $em->flush();
        }
    }

    public function onPhoneLog(PhoneLogEvent $event)
    {
        $log = $event->getPhoneLog();
        if ($log->getHangupDate()) {
            $number = 'incoming' === $log->getDirection() ? $log->getFromNumber() : $log->getToNumber();
            if (0 === mb_strpos($number, '45')) {
                $number = mb_substr($number, 2);
            }

            if (!$this->registerPhoneCall($number, $log)) {
                $this->sendCreateContact($number);
            }
        }
    }

    private function sendCreateContact(string $number): void
    {
        if (0 === $this->registry->getRepository(PhoneLogMailSent::class)->findSentByNumber($number, 48)) {
            $contact = new Contact();
            $contact->addPhonenumber((new ContactPhoneNumber())->setNumber($number));
            $this->mailer->createMessage($contact);
            if ($em = $this->registry->getManagerForClass(PhoneLogMailSent::class)) {
                $phonelog = new PhoneLogMailSent();
                $phonelog->setNumber($number);
                $em->persist($phonelog);
                $em->flush();
            }
        }
    }

    private function registerPhoneCall(string $number, PhoneLog $log): bool
    {
        if ($contactNumber = $this->registry->getRepository(ContactPhoneNumber::class)->findByNumber($number)) {
            if (!$call = $this->registry->getRepository(ContactPhoneCall::class)->findByUuid($log->getUuid())) {
                $call = new ContactPhoneCall();
                $call->setUuid($log->getUuid());
                $call->setContact($contactNumber->getContact());
            }

            if ($log->getDuration()) {
                $call->setDuration($log->getDuration());
            }

            if ($em = $this->registry->getManagerForClass(ContactPhoneCall::class)) {
                $em->persist($call);
                $em->flush();
            }

            return true;
        }

        return false;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PhoneLogEvent::NAME => [['setPhoneLog'], ['onPhoneLog']],
        ];
    }
}
