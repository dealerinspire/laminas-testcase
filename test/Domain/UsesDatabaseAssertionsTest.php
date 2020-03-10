<?php

namespace DiCommonTest\Test\Domain;

use DiCommonTest\Domain\TestCase;
use DiCommonTest\Domain\UsesDatabaseAssertions;
use DiCommonTest\Test\Entity\Suspect;

class UsesDatabaseAssertionsTest extends TestCase
{
    use UsesDatabaseAssertions, UsesModelFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->initModelFactory($this->getEntityManager(), $this->faker);
    }

    public function testEmptyTable()
    {
        /** @var Suspect $suspect */
        $suspect = $this->factory->create(Suspect::class);
        $count = $this->getEntityManager()->getRepository(Suspect::class)->count([]);
        $this->assertEquals(1, $count);

        $this->emptyTable(Suspect::class);

        $count = $this->getEntityManager()->getRepository(Suspect::class)->count([]);
        $this->assertEquals(0, $count);
    }

    public function testAssertFoundInDatabase()
    {
        /** @var Suspect $suspect */
        $suspect = $this->factory->create(Suspect::class);

        $this->assertFoundInDatabase(Suspect::class, [
            'firstName' => $suspect->getFirstName(),
            'lastName' => $suspect->getLastName(),
        ]);
    }

    public function testAssertNotFoundInDatabase()
    {
        $this->assertNotFoundInDatabase(Suspect::class, [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
        ]);
    }
}
