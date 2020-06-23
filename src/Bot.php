<?php
declare(strict_types=1);

namespace AnarchyService;

use AnarchyService\Base;
use AnarchyService\Send;
require_once 'src/bootstrap.php';
require_once 'src/Base.php';
require_once 'src/SendRequest/Send.php';
/**
 * Class Bot
 */
class Bot
{
    /**
     * config constructor.
     */
    public function __construct()
    {
        new Base((string)getenv('TOKEN'));
        return [
            'bot' => [
                'token' => (string)getenv('TOKEN'),
                'admins' => (array)array_filter(
                    array_map(
                        'trim',
                        explode(';', getenv('ADMINS'))
                    )
                ),
                'channel_username' => (string)getenv('CHANNEL_USERNAME'),
                'bot_username' => (string)getenv('BOT_USERNAME'),
            ],
            'server' => [
                'domain' => (string)getenv('DOMAIN'),
            ],
            'payment' => [
                'merchant_id' => (string)getenv('MERCHANT_ID'),
                'affiliates_days' => (string)getenv('AFFILIATES_DAYS'),
                'paygate' => (string)getenv('PAYGATE'),
            ],
            'db' => [
                'type' => getenv('DB_TYPE'),
                'mysql' => [
                    'host' => getenv('MYSQL_HOST'),
                    'port' => (int)getenv('MYSQL_PORT'),
                    'user' => getenv('MYSQL_USER'),
                    'password' => getenv('MYSQL_PASSWORD'),
                    'database' => getenv('MYSQL_DATABASE'),
                    'max_connections' => (int)getenv('MYSQL_MAX_CONNECTIONS'),
                    'idle_timeout' => (int)getenv('MYSQL_IDLE_TIMEOUT'),
                    'cache_ttl' => getenv('MYSQL_CACHE_TTL')
                ]
            ]
        ];
    }
}
