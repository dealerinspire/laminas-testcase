<?php

namespace DealerInspire\LaminasTestcase\Test\Domain;

use DealerInspire\LaminasTestcase\Domain\Bootstrap;
use DealerInspire\LaminasTestcase\Domain\TestCase;

class BootstrapTest extends TestCase
{
    public function testInit()
    {
        Bootstrap::init();
        $config = Bootstrap::getConfig();

        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey('module_listener_options', $config);
        $this->assertArrayHasKey('modules', $config);
    }
}
