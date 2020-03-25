<?php

namespace DealerInspire\LaminasTestcase\Domain;

use Laminas\Stdlib\ArrayUtils;

/**
 * Test bootstrap, for setting up Laminas configuration
 */
class Bootstrap
{
    protected static $serviceManager;
    protected static $config;

    public static function init()
    {
        $appConfig = include APPLICATION_PATH . '/config/application.config.php';

        $configFile = APPLICATION_PATH . '/config/' . APPLICATION_ENV . '.config.php';
        if (file_exists($configFile)) {
            $envConfig = include($configFile);
            $appConfig = ArrayUtils::merge(
                $appConfig,
                $envConfig
            );
        }

        // use ModuleManager to load this module and it's dependencies
        self::$config = [
            'module_listener_options' => [
                'config_glob_paths' => [
                    sprintf(
                        '%s/config/autoload/{,*.}{global,%s,local}.php',
                        APPLICATION_PATH,
                        APPLICATION_ENV
                    ),
                ],
                'module_paths' => [
                    APPLICATION_PATH . '/module',
                    APPLICATION_PATH . '/vendor',
                ],
            ],
            'modules' => $appConfig['modules'],
        ];

        if (isset($appConfig['zf-oauth2-doctrine'])) {
            self::$config['zf-oauth2-doctrine'] = $appConfig['zf-oauth2-doctrine'];
        }
    }

    public static function chroot()
    {
        $rootPath = dirname(static::findParentPath('module'));
        chdir($rootPath);
    }

    public static function getConfig()
    {
        return self::$config;
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}
