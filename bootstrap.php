<?php
declare(strict_types=1);
namespace AnarchyService;
use AnarchyService\Base;
use AnarchyService\BotException;

$root = __DIR__;
const ENV_VERSION='1';

//Composer init
{
    if (!file_exists($root . '/vendor/autoload.php')) {
        if (file_exists(__DIR__ . '/../../..' . '/vendor/autoload.php')) {
            $root = __DIR__ . '/../../..';
        } else {
            system('composer install -o --no-dev');
        }
    }

    define('ROOT_DIR', $root);
    chdir(ROOT_DIR);
    require_once ROOT_DIR . '/vendor/autoload.php';
}

//Config init
{
    if (!getenv('TOKEN')) {
        $envPath = 'env.php';
        $envPathExample = $envPath . '.example';

        if (!is_file($envPath) || filesize($envPath) === 0) {
            $envContent = file_get_contents($envPathExample);
            file_put_contents($envPath, $envContent);
        }
        require_once $envPath;
        if (getenv('VERSION') !== ENV_VERSION) {
            throw new BotException("Env version mismatch. Update env.php from env.php.example.\n
        VERSION in env.php:" . getenv('VERSION') . "\n
        required :" . ENV_VERSION);
        }
    }
}
if ($memoryLimit = getenv('MEMORY_LIMIT')) {
    ini_set('memory_limit', $memoryLimit);
}

if ($timezone = getenv('TIMEZONE')) {
    date_default_timezone_set($timezone);
}
