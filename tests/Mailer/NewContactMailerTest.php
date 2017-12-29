<?php

namespace App\tests\Mailer;

use App\Entity\Contact;
use App\Entity\ContactInfoField;
use App\Entity\ContactPhoneNumber;
use App\Mailer\NewContactMailer;
use App\tests\AbstractTest;
use PhpImap\IncomingMail;

class NewContactMailerTest extends AbstractTest
{
    public function testIsClass()
    {
        $this->assertInstanceOf(NewContactMailer::class, $this->getClass(NewContactMailer::class));
    }

    public function testCorrect2PhoneNumbers()
    {
        \bootstrap::createDatabase();

        $this->readMail('correct_2phonenumbers.txt');

        $contact = $this->getRepository(Contact::class)->findOneBy(['name' => 'correct 2phonenumbers']);
        $this->assertNotNull($contact);
        $this->assertSame('correct 2phonenumbers', $contact->getName());
        $this->assertSame('DonaldCompany', $contact->getCompanyName());
        $this->assertSame('www.donald.com', $contact->getWebpage());

        $this->assertCount(2, $contact->getPhonenumbers());
        /** @var ContactPhoneNumber $phone1 */
        $phone1 = $contact->getPhonenumbers()[0];
        $this->assertInstanceOf(ContactPhoneNumber::class, $phone1);
        $this->assertSame('40325541', $phone1->getNumber());
        $this->assertSame('Donald', $phone1->getName());

        /** @var ContactPhoneNumber $phone2 */
        $phone2 = $contact->getPhonenumbers()[1];
        $this->assertInstanceOf(ContactPhoneNumber::class, $phone2);
        $this->assertSame('78945543', $phone2->getNumber());
        $this->assertNull($phone2->getName());
    }

    public function testCorrectNoExtra()
    {
        \bootstrap::createDatabase();

        $this->readMail('correct_noextra.txt');

        $contact = $this->getRepository(Contact::class)->findOneBy(['name' => 'Donald Duck']);
        $this->assertNotNull($contact, 'Contact should not be null');
        $this->assertSame('Donald Duck', $contact->getName());
        $this->assertNull($contact->getCompanyName(), 'Should not have company name');
        $this->assertNull($contact->getWebpage(), 'Should not have webpage');

        $this->assertCount(1, $contact->getPhonenumbers());
        /** @var ContactPhoneNumber $phone1 */
        $phone1 = $contact->getPhonenumbers()[0];
        $this->assertInstanceOf(ContactPhoneNumber::class, $phone1);
        $this->assertSame('98765432', $phone1->getNumber());
        $this->assertSame('Donald Ducksi', $phone1->getName());
    }

    public function testNotenteredPhonenumber()
    {
        \bootstrap::createDatabase();

        $this->readMail('notentered_phonenumber.txt');
        $contacts = $this->getRepository(Contact::class)->findAll();
        $this->assertCount(0, $contacts);
    }

    public function testMissingContactDetails()
    {
        \bootstrap::createDatabase();

        $this->readMail('missing_contact_details.txt');
        $contacts = $this->getRepository(Contact::class)->findAll();
        $this->assertCount(0, $contacts);
    }

    public function testMissingPhonenumber()
    {
        \bootstrap::createDatabase();

        $this->readMail('missing_phonenumber.txt');
        $contacts = $this->getRepository(Contact::class)->findAll();
        $this->assertCount(0, $contacts);
    }

    public function testMissingExtraFields()
    {
        \bootstrap::createDatabase();
        $em = $this->getManager(ContactInfoField::class);

        $required = (new ContactInfoField())
            ->setName('required')
            ->setRequired(true)
            ->setType(ContactInfoField::TYPE_STRING)
        ;
        $em->persist($required);
        $em->flush();
        $this->assertCount(1, $this->getRepository(ContactInfoField::class)->findAll());

        $this->readMail('missing_extrafields.txt');
        $this->assertCount(0, $this->getRepository(Contact::class)->findAll());
    }

    public function testExtraFields()
    {
        \bootstrap::createDatabase();
        $em = $this->getManager(ContactInfoField::class);

        $required = (new ContactInfoField())
            ->setName('required')
            ->setRequired(true)
            ->setType(ContactInfoField::TYPE_STRING)
        ;
        $em->persist($required);

        $notrequired1 = (new ContactInfoField())
            ->setName('notrequired1')
            ->setRequired(false)
            ->setType(ContactInfoField::TYPE_STRING)
        ;
        $em->persist($notrequired1);

        $notrequired2 = (new ContactInfoField())
            ->setName('notrequired2')
            ->setRequired(false)
            ->setType(ContactInfoField::TYPE_STRING)
        ;
        $em->persist($notrequired2);
        $em->flush();

        $this->assertCount(3, $this->getRepository(ContactInfoField::class)->findAll());

        $this->readMail('extrafields.txt');
        /** @var Contact $contact */
        $contact = $this->getRepository(Contact::class)->findOneBy(['name' => 'extrafields']);
        $this->assertNotNull($contact);
        $this->assertCount(2, $contact->getInfos());

        foreach ($contact->getInfos() as $info) {
            switch ($info->getField()->getName()) {
                case 'required':
                    $this->assertSame('Required data is set', $info->getValue());
                    break;
                case 'notrequired1':
                    $this->assertSame('Not required is set', $info->getValue());
                    break;
                case 'notrequired2':
                    $this->assertNull($info->getValue());
                    break;
            }
        }
    }

    public function testWrongSubject()
    {
        \bootstrap::createDatabase();

        $incomingMail = new IncomingMail();
        $incomingMail->subject = 'Wrong subject';
        $incomingMail->textPlain = $this->getBody('correct_2phonenumbers.txt');
        $mailer = $this->getClass(NewContactMailer::class);
        $this->assertFalse($mailer->readMessage($incomingMail));
    }

    private function readMail($filename, NewContactMailer $mailer = null)
    {
        $incomingMail = new IncomingMail();
        $incomingMail->subject = NewContactMailer::SUBJECT;
        $incomingMail->textPlain = $this->getBody($filename);

        if (!$mailer) {
            $mailer = $this->getClass(NewContactMailer::class);
        }

        return $mailer->readMessage($incomingMail);
    }

    private function getBody($filename)
    {
        return file_get_contents(__DIR__.'/../data/incomingmail/'.$filename);
    }
}
