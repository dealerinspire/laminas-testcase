<?php

namespace DealerInspire\LaminasTestcase\Test\Domain;

use DealerInspire\LaminasTestcase\Domain\TestCase;
use Doctrine\ORM\EntityManager;
use Faker\Generator;
use GuzzleHttp\Client;
use Zend\ServiceManager\ServiceManager;
use Mockery\MockInterface;

class TestCaseTest extends TestCase
{
    protected $classVar;

    public function testGetServiceManager()
    {
        $this->assertInstanceOf(ServiceManager::class, $this->getServiceManager());
    }

    public function testGetEntityManager()
    {
        $this->assertInstanceOf(EntityManager::class, $this->getEntityManager());
    }

    public function testFaker()
    {
        $this->assertInstanceOf(Generator::class, $this->faker);
    }

    public function testSetNull()
    {
        // save this so we can call `rollback` in the teardown method
        $entityManager = $this->doctrineEm;

        $this->classVar = $this->faker->uuid;

        $this->setNull();

        $this->assertNull($this->doctrineEm);
        $this->assertNull($this->serviceManager);
        $this->assertNull($this->classVar);

        // restore class vars that we will need
        $this->doctrineEm = $entityManager;
    }

    public function testMock()
    {
        $mock = $this->mock(Client::class);

        $this->assertInstanceOf(MockInterface::class, $mock);

        $service = $this->getServiceManager()->get(Client::class);

        $this->assertInstanceOf(MockInterface::class, $service);
        $this->assertSame($mock, $service);
    }

    public function testConfig()
    {
        $key = $this->faker->uuid;
        $value = $this->faker->uuid;

        $this->config($key, $value);
        $config = $this->config();

        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey($key, $config);
        $this->assertEquals($config[$key], $value);
    }
}
