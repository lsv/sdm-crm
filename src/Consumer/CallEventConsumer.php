<?php

namespace App\Consumer;

use App\Entity\PhoneLog;
use App\Events\PhoneLogEvent;
use Doctrine\Common\Persistence\ManagerRegistry;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CallEventConsumer implements ConsumerInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $dispatcher)
    {
        $this->registry = $registry;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param AMQPMessage $msg The message
     *
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {
        $json = json_decode($msg->body);
        $uuid = $json->data->call_uuid;

        /** @var PhoneLog $log */
        if (!$log = $this->registry->getRepository(PhoneLog::class)->findOneBy(['uuid' => $uuid])) {
            $log = new PhoneLog();
            $log->setUuid($uuid);
            $log->setCallDate(new \DateTime($json->timestamp));
        }

        $event = new PhoneLogEvent($log, $json);
        $this->dispatcher->dispatch(
            $event::NAME,
            $event
        );

        return true;
    }
}
