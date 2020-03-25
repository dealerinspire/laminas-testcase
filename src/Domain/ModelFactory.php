<?php

namespace DealerInspire\LaminasTestcase\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Generator;

class ModelFactory
{
    /**
     * @var EntityManagerInterface
     */
    private $doctrineEm;
    /**
     * @var array
     */
    private $factories = [];
    /**
     * @var Generator
     */
    private $faker;

    public function __construct(EntityManagerInterface $doctrineEm, Generator $faker)
    {
        $this->doctrineEm = $doctrineEm;
        $this->faker = $faker;
    }

    /**
     * Instantiates and populates the entity but does not save to the Db.
     *
     * @param $entity
     * @param $attributes
     * @return object instance of the requested entity
     * @throws Exception
     */
    public function make($entity, $attributes = [])
    {
        $factory = $this->getFactory($entity);
        $attrs = array_merge($factory($this->faker), $attributes);

        $instance = $this->getInstance($entity);
        $this->fill($instance, $attrs);

        return $instance;
    }

    /**
     * Instantiates, populates and saves the entity.
     *
     * @param $entity
     * @param $attributes
     * @return object instance of the requested entity
     * @throws Exception
     */
    public function create($entity, $attributes = [])
    {
        $instance = $this->make($entity, $attributes);

        $this->doctrineEm->persist($instance);
        $this->doctrineEm->flush();

        return $instance;
    }

    /**
     * Register a callable factory for the entity. The callable will be passed
     * an instance of Faker and must return an array of attributes for the
     * entity.
     *
     * @param $entity
     * @param $callable
     * @return $this
     */
    public function setFactory($entity, $callable)
    {
        $this->factories[$entity] = $callable;
        return $this;
    }

    /**
     * Get the factory for the given entity.
     *
     * @param $entity
     * @return mixed
     * @throws Exception
     */
    public function getFactory($entity)
    {
        if (!array_key_exists($entity, $this->factories)) {
            throw new Exception($entity . ' is not registered with the Model Factory');
        }

        return $this->factories[$entity];
    }

    /**
     * Attempt to instantiate the given entity.
     *
     * @param $entity
     * @return object
     * @throws Exception
     */
    protected function getInstance($entity)
    {
        try {
            return new $entity();
        } catch (Exception $e) {
            throw new Exception("$entity could not be instantiated.");
        }
    }

    /**
     * Attempt to populate the attributes of the entity.
     *
     * @param $instance
     * @param array $attributes
     * @throws Exception
     */
    protected function fill($instance, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $method1 = 'set' . $this->titleCase($key);
            if (is_callable([$instance, $method1])) {
                $instance->$method1($value);
                continue;
            }

            $method2 = 'set' . $this->titleSnakeCase($key);
            if (is_callable([$instance, $method2])) {
                $instance->$method2($value);
                continue;
            }

            throw new Exception("Cannot set $key with $method1 or $method2");
        }
    }

    /**
     * Return a TitleCase version of a snake_case subject.
     *
     * @param $subject
     * @return mixed
     */
    protected function titleCase($subject)
    {
        $words = str_replace(['-', '_'], ' ', $subject);
        return str_replace(' ', '', ucwords($words));
    }

    /**
     * Return a Title_Snake_Case version of a snake_case subject because Doctrine Hydration.
     *
     * @param $subject
     * @return mixed
     */
    protected function titleSnakeCase($subject)
    {
        $words = str_replace(['-', '_'], ' ', $subject);
        return str_replace(' ', '_', ucwords($words));
    }
}
