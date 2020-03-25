<?php

namespace DealerInspire\LaminasTestcase\Domain;

use Countable;
use Doctrine\ORM\QueryBuilder;

trait UsesDatabaseAssertions
{
    /**
     * Delete all rows from a table (so a "count" test will succeed).
     *
     * @param string $entity Class name to identify table
     */
    protected function emptyTable($entity)
    {
        /** @noinspection SqlWithoutWhere */
        $this->doctrineEm->createQuery("delete from $entity")->execute();
    }

    /**
     * Verifies that a row exists in the database with all of the values
     * specified in the data array. Method name replicates Laravel assertion.
     *
     * @param string $entity
     * @param array $data
     */
    protected function seeInDatabase($entity, array $data)
    {
        $this->assertCount(
            1,
            $this->checkDatabaseForEntity($entity, $data),
            "Your $entity entity was not found in the database, but should be"
        );
    }

    /**
     * An 'assert' wrapper for the 'seeInDatabase' method
     *
     * @param string $entity
     * @param array $data
     */
    protected function assertFoundInDatabase($entity, array $data)
    {
        $this->seeInDatabase($entity, $data);
    }

    /**
     * Verifies that a row does not exist in the database with all of the values
     * specified in the data array.
     *
     * @param string $entity
     * @param array $data
     */
    protected function assertNotFoundInDatabase($entity, array $data)
    {
        $this->assertCount(
            0,
            $this->checkDatabaseForEntity($entity, $data),
            "The $entity entity was found in the database; it shouldn't be there."
        );
    }

    /**
     * Runs an entity query in the database
     *
     * @param string $entity
     * @param array $data
     * @return Countable
     */
    protected function checkDatabaseForEntity($entity, array $data)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->doctrineEm->createQueryBuilder()
            ->select('ent')
            ->from($entity, 'ent');

        foreach ($data as $key => $datum) {
            $queryBuilder->andWhere('ent.' . $key . ' = :' . $key)
                ->setParameter($key, $datum);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
