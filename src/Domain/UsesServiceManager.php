<?php

namespace DealerInspire\LaminasTestcase\Domain;

use Mockery;
use Laminas\ServiceManager\ServiceManager;

trait UsesServiceManager
{
    /**
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
        throw new \Exception('getServiceManager method must be defined in class');
    }

    /**
     * Mock a class and make sure the Service Locator will use the mock instance.
     *
     * @param string $className
     * @return Mockery\MockInterface
     */
    protected function mock($className, $arguments = null)
    {
        if (is_array($arguments)) {
            $mock = Mockery::mock($className, $arguments);
        } else {
            $mock = Mockery::mock($className);
        }
        $this->setService($className, $mock);

        return $mock;
    }

    /**
     * Change a value in the application global config. Returns the global config array.
     *
     * @param string $key
     * @param mixed $value
     * @return array
     */
    protected function config($key = null, $value = null)
    {
        $config = $this->getServiceManager()->get('config');

        if ($key !== null) {
            $config[$key] = $value;
            $this->setService('config', $config);
        }

        return $config;
    }

    /**
     * Override a service in the Service Locator.
     *
     * @param string $serviceName
     * @param mixed $service
     * @return $this
     */
    protected function setService($serviceName, $service)
    {
        $serviceManager = $this->getServiceManager();
        $serviceManager->setAllowOverride(true);

        $serviceManager->setService($serviceName, $service);

        $serviceManager->setAllowOverride(false);

        return $this;
    }
}
