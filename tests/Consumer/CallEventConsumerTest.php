<?php

namespace App\Tests\Consumer;

use App\Consumer\CallEventConsumer;
use App\Entity\Contact;
use App\Entity\ContactPhoneNumber;
use App\Entity\PhoneLog;
use App\Entity\PhoneLogMailSent;
use App\tests\AbstractTest;
use PhpAmqpLib\Message\AMQPMessage;

class CallEventConsumerTest extends AbstractTest
{
    public function testIsClass()
    {
        $this->assertInstanceOf(CallEventConsumer::class, $this->getClass(CallEventConsumer::class));
    }

    public function testConsume()
    {
        $consumer = $this->getClass(CallEventConsumer::class);

        /** @var PhoneLog[] $phonelogs */
        $phonelogs = $this->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(0, $phonelogs);

        $message = new AMQPMessage();
        $message->setBody($this->getCallJson('12345', 'new'));
        $consumer->execute($message);
        $phonelogs = $this->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(1, $phonelogs);
        $this->assertSame('12345', $phonelogs[0]->getUuid());
        $mailsent = $this->getRepository(PhoneLogMailSent::class)->findAll();
        $this->assertCount(0, $mailsent);

        $message = new AMQPMessage();
        $message->setBody($this->getCallJson('12345', 'ringing'));
        $consumer->execute($message);
        $phonelogs = $this->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(1, $phonelogs);
        $this->assertCount(2, $phonelogs[0]->getStatuses());
        $mailsent = $this->getRepository(PhoneLogMailSent::class)->findAll();
        $this->assertCount(0, $mailsent);

        $message = new AMQPMessage();
        $message->setBody($this->getCallJson('12345', 'answer'));
        $consumer->execute($message);
        $phonelogs = $this->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(1, $phonelogs);
        $this->assertCount(3, $phonelogs[0]->getStatuses());
        $mailsent = $this->getRepository(PhoneLogMailSent::class)->findAll();
        $this->assertCount(0, $mailsent);

        $message = new AMQPMessage();
        $message->setBody($this->getCallJson('12345', 'hangup'));
        $consumer->execute($message);
        $phonelogs = $this->getRepository(PhoneLog::class)->findAll();
        $this->assertCount(1, $phonelogs);
        $this->assertCount(4, $phonelogs[0]->getStatuses());
        $mailsent = $this->getRepository(PhoneLogMailSent::class)->findAll();
        $this->assertCount(1, $mailsent);
    }

    public function testContactExistConsume()
    {
        $contact = (new Contact())
            ->setName('name')
            ->setCompanyName('company')
            ->addPhonenumber((new ContactPhoneNumber())->setName('phone')->setNumber('12345678'))
        ;

        $em = $this->getManager(Contact::class);
        $this->assertNotNull($em);
        $em->persist($contact);
        $em->flush();

        $consumer = $this->getClass(CallEventConsumer::class);
        $consumer->execute((new AMQPMessage())->setBody($this->getCallJson('54321', 'new')));
        $consumer->execute((new AMQPMessage())->setBody($this->getCallJson('54321', 'ringing')));
        $consumer->execute((new AMQPMessage())->setBody($this->getCallJson('54321', 'answer')));
        $consumer->execute((new AMQPMessage())->setBody($this->getCallJson('54321', 'hangup')));

        $newContact = $this->getRepository(Contact::class)->findOneBy(['companyName' => 'company']);
        $this->assertNotNull($newContact);
        $this->assertCount(1, $newContact->getPhonecalls());
    }

    private function getCallJson($uuid, $type)
    {
        switch ($type) {
            case 'new':
            case 'ringing':
            case 'answer':
            case 'hangup':
                $body = file_get_contents(__DIR__.'/../data/callevent/call-event.call.'.$type.'.json');
                $body = json_decode($body);
                $body->data->call_uuid = $uuid;

                return json_encode($body);
            default:
                throw new \InvalidArgumentException('Call type "'.$type.'" is not allowed');
        }
    }
}
