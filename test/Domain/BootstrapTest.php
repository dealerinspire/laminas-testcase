<?php

namespace DiCommonTest\Test\Domain;

use DiCommonTest\Domain\Bootstrap;
use DiCommonTest\Domain\TestCase;

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
