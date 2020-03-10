<?php

namespace DiCommonTest\Test\Domain;

use DiCommonTest\Domain\ModelFactory;
use DiCommonTest\Domain\TestCase;
use DiCommonTest\Domain\UsesDatabaseAssertions;
use DiCommonTest\Test\Entity\Suspect;
use Faker\Generator;

class ModelFactoryTest extends TestCase
{
    use UsesDatabaseAssertions, UsesModelFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->initModelFactory($this->getEntityManager(), $this->faker);
    }

    public function testGetFactory()
    {
        $method = $this->factory->getFactory(Suspect::class);

        $this->assertTrue(is_callable($method));
    }

    public function testMake()
    {
        /** @var Suspect $object */
        $object = $this->factory->make(Suspect::class);

        $this->assertInstanceOf(Suspect::class, $object);
        $this->assertNotFoundInDatabase(Suspect::class, [
            'firstName' => $object->getFirstname(),
            'lastName' => $object->getLastName(),
        ]);
    }

    public function testCreate()
    {
        /** @var Suspect $object */
        $object = $this->factory->create(Suspect::class);

        $this->assertInstanceOf(Suspect::class, $object);
        $this->assertFoundInDatabase(Suspect::class, [
            'firstName' => $object->getFirstname(),
            'lastName' => $object->getLastName(),
        ]);
    }
}
