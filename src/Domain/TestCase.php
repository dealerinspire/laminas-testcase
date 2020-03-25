<?php

namespace DiCommonTest\Domain;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionObject;

class TestCase extends BaseTestCase
{
    use UsesServiceManager;
    use MockeryPHPUnitIntegration;

    /** @var ServiceManager */
    protected $serviceManager;
    /** @var EntityManager */
    protected $doctrineEm;
    /** @var Generator */
    protected $faker;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->serviceManager = $this->getServiceManager();

        $this->doctrineEm = $this->getEntityManager();

        $this->doctrineEm->beginTransaction();

        $this->faker = Factory::create('en_US');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->doctrineEm->rollback();
        $this->doctrineEm = null;

        $this->serviceManager = null;

        $this->faker = null;

        $this->closeMockery();

        parent::tearDown();

        $this->setNull();
        gc_collect_cycles();
    }

    /**
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
        if (!isset($this->serviceManager)) {
            $this->serviceManager = new ServiceManager(new ServiceManagerConfig());
            $this->serviceManager->setService('ApplicationConfig', Bootstrap::getConfig());
            $this->serviceManager->get('ModuleManager')->loadModules();
        }

        return $this->serviceManager;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceManager()->get(EntityManager::class);

        if (!$entityManager->isOpen()) {
            // a test has crashed the entity manager; need to recreate it
            $entityManager = EntityManager::create(
                $entityManager->getConnection(),
                $entityManager->getConfiguration()
            );

            // overwrite the copy held by the ServiceManager so we don't
            // have to create the EntityManager over and over again with each test
            $this->setService(EntityManager::class, $entityManager);
        }

        return $entityManager;
    }

    /**
     * Set class variables to null for PHP garbage collector.
     */
    protected function setNull()
    {
        // clean up class storage
        // http://kriswallsmith.net/post/18029585104/faster-phpunit
        $refl = new ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            // skip static and vars belonging to PhpUnit
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
    }
}
