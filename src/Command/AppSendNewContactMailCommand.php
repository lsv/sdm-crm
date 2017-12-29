<?php

namespace App\Command;

use App\Entity\Contact;
use App\Entity\ContactInfo;
use App\Entity\ContactInfoField;
use App\Mailer\NewContactMailer;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppSendNewContactMailCommand extends Command
{
    protected static $defaultName = 'app:send-new-contact-mail';

    /**
     * @var NewContactMailer
     */
    private $mailer;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(NewContactMailer $mailer, ManagerRegistry $registry)
    {
        $this->mailer = $mailer;
        $this->registry = $registry;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send new mail to create a new contact')
            ->addOption('filled', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Filled fields', [])
            ->addArgument('email', InputArgument::OPTIONAL, 'Email address to send to')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name to the email address')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $email = $input->getArgument('email') ?: 'maa@scandesigns.dk';
        $name = $input->getArgument('name') ?: 'Martin';
        $contact = new Contact();

        if (\is_array($input->getOption('filled'))) {
            foreach ($input->getOption('filled') as $filled) {
                if (false !== mb_strpos($filled, '=')) {
                    [$type, $value] = explode('=', $filled);
                    $this->setField($contact, $type, $value);
                }
            }
        }

        $output->writeln($this->mailer->createMessage($contact, $email, $name));
    }

    private function setField(Contact $contact, $type, $value)
    {
        if (!$infoField = $this->registry->getRepository(ContactInfoField::class)->findOneBy(['name' => $type])) {
            throw new \InvalidArgumentException('Type "'.$type.'" not a valid field');
        }

        $contactField = new ContactInfo();
        $contactField->setValueByType($infoField, $value);
        $contact->addInfo($contactField);
    }
}
