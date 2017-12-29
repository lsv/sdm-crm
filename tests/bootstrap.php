<?php

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\DropSchemaDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

require __DIR__.'/../vendor/autoload.php';

class bootstrap extends WebTestCase
{
    public static function createDatabase()
    {
        self::bootKernel();

        $command = new DropSchemaDoctrineCommand();
        $command->setApplication(new Application(self::$kernel));
        $command->run(new StringInput('--force'), new NullOutput());

        $command = new CreateSchemaDoctrineCommand();
        $command->setApplication(new Application(self::$kernel));
        $command->run(new StringInput(''), new NullOutput());
    }
}

bootstrap::createDatabase();
