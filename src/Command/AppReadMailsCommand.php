<?php

namespace App\Command;

use App\Mailer\NewContactMailer;
use SecIT\ImapBundle\Service\Imap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppReadMailsCommand extends Command
{
    protected static $defaultName = 'app:read-mails';

    /**
     * @var Imap
     */
    private $imap;
    /**
     * @var NewContactMailer
     */
    private $mailer;

    public function __construct(Imap $imap, NewContactMailer $mailer)
    {
        $this->imap = $imap;
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('type', InputArgument::OPTIONAL, 'Which mode do you want?', 'read')
            ->setDescription('Read mails')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('type')) {
            case 'read':
                return $this->read($input, $output);
            case 'test':
                return $this->test($input, $output);
            default:
                $output->writeln('Type not valid');
                exit(1);
        }
    }

    private function read(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $mailbox = $this->imap->get('mailer');
            foreach ($mailbox->searchMailbox() as $mailId) {
                $mail = $mailbox->getMail($mailId, false);
                if ($this->mailer->readMessage($mail)) {
                    $mailbox->deleteMail($mailId);
                }
            }
        } catch (\Exception $e) {
            $io->caution('Exception: '.$e->getMessage());
        }
    }

    private function test(InputInterface $input, OutputInterface $output)
    {
        try {
            $io = new SymfonyStyle($input, $output);
            if ($this->imap->testConnection('mailer')) {
                $io->success('Connection successful');
            } else {
                $io->caution('Connection not successful');
            }
        } catch (\Exception $e) {
            $output->writeln('Exception: '.$e->getMessage());
        }
    }
}
