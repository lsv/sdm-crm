<?php

namespace App\Tests\Consumer;

use App\Consumer\CallEventConsumer;
use App\Entity\PhoneLog;
use App\Entity\PhoneLogMailSent;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CallEventConsumerTest extends WebTestCase
{
    public function testIsConsumer()
    {
        $client = self::createClient();
        $consumer = $client->getContainer()->get(CallEventConsumer::class);

        $this->assertInstanceOf(CallEventConsumer::class, $consumer);
    }

    public function testConsume()
    {
        $client = self::createClient();
        $consumer = $client->getContainer()->get(CallEventConsumer::class);

        /** @var PhoneLog[] $phonelogs */
        $phonelogs = $client->getContainer()->get('doctrine')->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(0, $phonelogs);

        $message = new AMQPMessage();
        $message->setBody(file_get_contents(__DIR__.'/../json/call-event.call.new.json'));
        $consumer->execute($message);
        $phonelogs = $client->getContainer()->get('doctrine')->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(1, $phonelogs);
        $mailsent = $client->getContainer()->get('doctrine')->getRepository(PhoneLogMailSent::class)->findAll();
        $this->assertCount(0, $mailsent);

        $message = new AMQPMessage();
        $message->setBody(file_get_contents(__DIR__.'/../json/call-event.call.ringing.json'));
        $consumer->execute($message);
        $phonelogs = $client->getContainer()->get('doctrine')->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(1, $phonelogs);
        $this->assertCount(2, $phonelogs[0]->getStatuses());
        $mailsent = $client->getContainer()->get('doctrine')->getRepository(PhoneLogMailSent::class)->findAll();
        $this->assertCount(0, $mailsent);

        $message = new AMQPMessage();
        $message->setBody(file_get_contents(__DIR__.'/../json/call-event.call.answer.json'));
        $consumer->execute($message);
        $phonelogs = $client->getContainer()->get('doctrine')->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(1, $phonelogs);
        $this->assertCount(3, $phonelogs[0]->getStatuses());
        $mailsent = $client->getContainer()->get('doctrine')->getRepository(PhoneLogMailSent::class)->findAll();
        $this->assertCount(0, $mailsent);

        $message = new AMQPMessage();
        $message->setBody(file_get_contents(__DIR__.'/../json/call-event.call.hangup.json'));
        $consumer->execute($message);
        $phonelogs = $client->getContainer()->get('doctrine')->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(1, $phonelogs);
        $this->assertCount(4, $phonelogs[0]->getStatuses());
        $mailsent = $client->getContainer()->get('doctrine')->getRepository(PhoneLogMailSent::class)->findAll();
        $this->assertCount(1, $mailsent);
    }
}
