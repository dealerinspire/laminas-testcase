<?php

use DealerInspire\LaminasTestcase\Domain\Bootstrap;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'development');
}

if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

require(APPLICATION_PATH . '/vendor/autoload.php');

Bootstrap::init();
//Bootstrap::chroot();
