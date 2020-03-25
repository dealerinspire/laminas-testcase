<?php

namespace DealerInspire\LaminasTestcase\Test\Domain;

use DealerInspire\LaminasTestcase\Test\Entity\Suspect;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use DealerInspire\LaminasTestcase\Domain\ModelFactory;

trait UsesModelFactory
{
    /** @var ModelFactory */
    protected $factory;

    /**
     * @param EntityManagerInterface $doctrineEm
     * @param Generator $faker
     */
    protected function initModelFactory(EntityManagerInterface $doctrineEm, Generator $faker)
    {
        $this->factory = new ModelFactory($doctrineEm, $faker);

        $this->factory->setFactory(Suspect::class, [$this, 'suspectFactory']);
    }

    public function suspectFactory(Generator $faker)
    {
        return [
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
        ];
    }
}
