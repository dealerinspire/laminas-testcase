<?php

namespace DiCommonTest\Domain;

use ReflectionClass;

trait UsesReflection
{
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method
     * @return mixed Method return.
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Get current value of a protected or private property of a class.
     *
     * @param object &$object      Instantiated object that we will get property from
     * @param string $propertyName Property name to access
     * @return mixed
     */
    protected function getProperty(&$object, $propertyName)
    {
        $reflection = new ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Set value of a protected or private property of a class.
     *
     * @param object &$object      Instantiated object that we will set property on
     * @param string $propertyName Property name to access
     * @param array  $value        Value that will the property will be set to
     */
    protected function setProperty(&$object, $propertyName, $value)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }
}
