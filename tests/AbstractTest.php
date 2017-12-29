<?php

namespace App\tests;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractTest extends WebTestCase
{
    /**
     * @param string $className
     *
     * @return mixed
     */
    protected function getClass($className)
    {
        $client = self::createClient();
        $container = $client->getContainer();
        $this->assertNotNull($container);
        if ($container->has($className)) {
            $object = $container->get($className);
        } else {
            $object = $container->get('test.'.$className);
        }
        $this->assertNotNull($object);

        return $object;
    }

    /**
     * @param string $className
     *
     * @return ObjectRepository
     */
    protected function getRepository($className): ObjectRepository
    {
        return $this->getClass('doctrine')->getRepository($className);
    }

    /**
     * @param string $className
     *
     * @return ObjectManager
     */
    protected function getManager($className)
    {
        return $this->getClass('doctrine')->getManagerForClass($className);
    }
}
