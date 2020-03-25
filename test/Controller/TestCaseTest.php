<?php

namespace DealerInspire\LaminasTestcase\Test\Controller;

use DealerInspire\LaminasTestcase\Controller\TestCase;
use DealerInspire\LaminasTestcase\Domain\Bootstrap;
use Doctrine\ORM\EntityManager;
use Faker\Generator;
use GuzzleHttp\Client;
use Zend\ServiceManager\ServiceManager;
use Mockery\MockInterface;

class TestCaseTest extends TestCase
{
    protected $classVar;

    public function setUp(): void
    {
        // for the tests, we will use our Bootstrap config and block
        // the TestCase from overwriting with the application config
        $this->setApplicationConfig(Bootstrap::getConfig());
        parent::setUp();
    }

    public function setApplicationConfig($applicationConfig)
    {
        // if we already set the config (in our setUp method), we will
        // silently block any further attempts to change it
        if (empty($this->applicationConfig)) {
            parent::setApplicationConfig($applicationConfig);
        }
        return $this;
    }

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

    public function testSetAuthorizationToken()
    {
        // @todo: zf-oauth2-doctrine AbstractMapper is looking for a relationship
        // between client_oauth2 and publickey_oauth2. The mapping explicitly marks
        // this relationship as "not required."
        $this->markTestSkipped('test fails on relationship');

        $this->setAuthorizationToken();

        $token = $this->getRequest()->getHeaders('Authorization');
        $this->assertNotNull($token);
        $this->assertInternalType('string', $token);
        $this->assertEquals('Bearer ', substr($token, 0, 7));
    }

}
