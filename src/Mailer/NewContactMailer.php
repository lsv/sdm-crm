<?php

namespace App\Mailer;

use App\Entity\Contact;
use App\Entity\ContactInfo;
use App\Entity\ContactInfoField;
use App\Entity\ContactPhoneNumber;
use Doctrine\Common\Persistence\ManagerRegistry;
use PhpImap\IncomingMail;

class NewContactMailer
{
    public const SUBJECT = '[NC - SD CRM] New Contact';

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var InfoFields
     */
    private $fields;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(Mailer $mailer, InfoFields $fields, \Twig_Environment $twig, ManagerRegistry $registry)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->fields = $fields;
        $this->registry = $registry;
    }

    /**
     * @param Contact     $contact
     * @param string|null $recieverAddress
     * @param string|null $recieverName
     * @param array|null  $missingData
     *
     * @return int
     */
    public function sendMessage(Contact $contact, ?string $recieverAddress = null, ?string $recieverName = null, array $missingData = null): int
    {
        try {
            $template = $this->twig->resolveTemplate('Mailer/new_contact_mail.mail.twig');
        } catch (\Twig_Error_Loader | \Twig_Error_Syntax $exception) {
            echo $exception->getMessage();
            exit;
        }

        $data = [
            'missingData' => $missingData,
            'contact' => $contact,
            'contactinfo' => $this->fields->getContactFields(),
            'required' => $this->fields->getRequiredInfoFields(),
            'optional' => $this->fields->getOptionalInfoFields(),
        ];

        $subject = self::SUBJECT;
        $plain = $template->renderBlock('body_plain', $data);

        $recieverAddress = $recieverAddress ?: $this->mailer->getStandardAddress();
        $recieverName = $recieverName ?: $this->mailer->getStandardName();

        $message = new \Swift_Message();
        $message->setTo($recieverAddress, $recieverName);
        $message->setSubject($subject);
        $message->setBody($plain, 'text/plain', 'utf8');

        return $this->mailer->send($message);
    }

    /**
     * @param IncomingMail $mail
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function readMessage(IncomingMail $mail): bool
    {
        $endPattern = '(\[x]|\[pn]|\[cd]|--)';

        if (0 === mb_strpos($mail->subject, self::SUBJECT)) {
            if ($em = $this->registry->getManagerForClass(Contact::class)) {
                $missingRequired = [];
                $contact = new Contact();
                $text = $mail->textPlain;

                // [x] fields
                /** @var ContactInfoField[] $fields */
                $fields = array_merge($this->fields->getRequiredInfoFields(), $this->fields->getOptionalInfoFields());
                foreach ($fields as $field) {
                    $pattern = preg_quote('[x] '.$field->getName().':', '/');
                    preg_match_all('/'.$pattern.'(.*?)'.$endPattern.'/msi', $text, $matches);
                    if (isset($matches[1][0]) && trim($matches[1][0])) {
                        $contact->addInfo((new ContactInfo())
                            ->setValueByType($field, preg_replace('#\R{2,}#', "\n", trim($matches[1][0])))
                        );
                    } elseif ($field->isRequired()) {
                        $missingRequired[] = $field->getName();
                    }
                }

                $phones = [];
                preg_match_all("/\[pn]:(.*?)(|(.*?)?)\n/msi", $text, $matches, PREG_OFFSET_CAPTURE);
                if (isset($matches[2]) && \is_array($matches[2])) {
                    foreach ($matches[2] as $match) {
                        $variables = array_map('trim', explode('|', $match[0]));
                        if (!empty($variables[0]) && 'phonenumber' !== $variables[0]) {
                            $phones[] = (new ContactPhoneNumber())
                                ->setNumber($variables[0])
                                ->setName(!empty($variables[1]) && 'name' !== $variables[1] ? $variables[1] : null)
                            ;
                        }
                    }
                }

                if ($phones) {
                    $contact->setPhonenumbers($phones);
                } else {
                    $missingRequired[] = 'Phonenumbers';
                }

                foreach ($this->fields->getContactFields() as $name => $data) {
                    $pattern = preg_quote('[cd] '.$name.':', '/');
                    preg_match_all('/'.$pattern.'(.*?)'.$endPattern.'/msi', $text, $matches);
                    if (isset($matches[1][0]) && trim($matches[1][0])) {
                        $setter = 'set'.ucfirst($name);
                        $contact->{$setter}(preg_replace('#\R{2,}#', "\n", trim($matches[1][0])));
                    } elseif ($data['required']) {
                        $missingRequired[] = $name;
                    }
                }

                if (!$missingRequired) {
                    $em->persist($contact);
                    $em->flush();

                    return true;
                }

                return $this->sendMessage($contact, $mail->fromAddress, $mail->fromName, $missingRequired) > 0;
            }
        }

        return false;
    }
}
