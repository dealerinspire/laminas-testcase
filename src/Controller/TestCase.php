<?php

namespace DiCommonTest\Controller;

use DiCommonTest\Domain\UsesServiceManager;
use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Mockery;
use OAuth2\ResponseType\AccessToken;
use ZF\OAuth2\Doctrine\Adapter\DoctrineAdapter;
use ZF\OAuth2\Doctrine\Entity\Client;

class TestCase extends AbstractHttpControllerTestCase
{
    use UsesServiceManager;
    use UsesResponseAssertions;

    protected $traceError = true;
    /** @var EntityManager */
    protected $doctrineEm;
    /** @var Generator */
    protected $faker;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->setApplicationConfig(
            include APPLICATION_PATH . '/config/application.config.php'
        );
        parent::setUp();

        $this->doctrineEm = $this->getServiceManager()->get(EntityManager::class);
        $this->doctrineEm->beginTransaction();

        $this->faker = Factory::create('en-US');
    }

    /**
     * @inheritDoc
     */
    public function tearDown()
    {
        $this->doctrineEm->rollback();

        Mockery::close();

        parent::tearDown();
    }

    /**
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
        return $this->getApplicationServiceLocator();
    }

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
        $client->setClientId($this->faker->uuid);

        // use the Doctrine OAuth2 Storage Adapter
        /** @var DoctrineAdapter $storage */
        $storage = $this->getServiceManager()->get(DoctrineAdapter::class);

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
