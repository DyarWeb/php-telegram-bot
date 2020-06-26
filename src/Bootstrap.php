<?php
declare(strict_types=1);

use AnarchyService\BotException;

const ENV_VERSION = '1';
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
    if ($memoryLimit = getenv('MEMORY_LIMIT')) {
        ini_set('memory_limit', $memoryLimit);
    }

    if ($timezone = getenv('TIMEZONE')) {
        date_default_timezone_set($timezone);
    }
}