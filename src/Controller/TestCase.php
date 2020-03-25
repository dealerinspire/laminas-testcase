<?php

namespace DiCommonTest\Controller;

use DiCommonTest\Domain\UsesDatabaseAssertions;
use DiCommonTest\Domain\UsesServiceManager;
use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use OAuth2\ResponseType\AccessToken;
use ReflectionObject;
use ZF\OAuth2\Doctrine\Adapter\DoctrineAdapter;
use ZF\OAuth2\Doctrine\Entity\Client;

class TestCase extends AbstractHttpControllerTestCase
{
    use UsesServiceManager;
    use UsesResponseAssertions;
    use MockeryPHPUnitIntegration;

    protected $traceError = true;
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
        $this->setApplicationConfig(
            include APPLICATION_PATH . '/config/application.config.php'
        );
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
        return $this->getApplicationServiceLocator();
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

    use UsesDatabaseAssertions;

    /**
     * Will set a Bearer token in the Authorization header.
     *
     * @return $this
     */
    protected function setAuthorizationToken()
    {
        // creating a client that is not attached to a user
        // only because we don't need the user to authenticate
        $client = new Client();
        $client->setClientId($this->faker->numberBetween(1, 999999));

        // use the Doctrine OAuth2 Storage Adapter
        /** @var DoctrineAdapter $storage */
        $storage = $this->getServiceManager()->get(DoctrineAdapter::class);
        $storage->getObjectManager()->persist($client);
        $storage->getObjectManager()->flush();

        // access_lifetime is in seconds. 10 seconds should be enough for a test.
        $config = ['access_lifetime' => 10, 'token_type' => 'Bearer'];
        $accessToken = new AccessToken($storage, $storage, $config);

        // this is going to bypass a lot of validation and Request manipulation
        // and just force-create an access token for the given client
        $token = $accessToken->createAccessToken($client->getClientId(), '', null, false);

        // put the access token into a header where authentication mechanism will find it
        $this->getRequest()->getHeaders()
            ->addHeaderLine('Authorization', 'Bearer ' . $token['access_token']);

        return $this;
    }

    /**
     * Set the Accept: application/json header.
     *
     * @return $this
     */
    protected function acceptJson()
    {
        $this->getRequest()->getHeaders()
            ->addHeaderLine('Accept', 'application/json');

        return $this;
    }

    /**
     * Set the Content-Type header and encode data as JSON.
     *
     * @param array $data
     * @return $this
     */
    protected function sendJson(array $data)
    {
        $this->getRequest()->setContent(json_encode($data));
        $this->getRequest()->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');

        return $this;
    }
}
