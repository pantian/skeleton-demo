<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */
namespace App\Listens;



use PTFramework\Config;
use PTFramework\Singleton;
use Simps\DB\PDO;
use Simps\DB\Redis;

class Pool
{
    use Singleton;
    public function workerStart($server, $workerId)
    {


        $config = Config::getInstance()->get('database',[]);
        if (! empty($config)) {
            PDO::getInstance($config);
        }

        $config = Config::getInstance()->get('redis', []);
        if (! empty($config)) {
            Redis::getInstance($config);
        }
    }
}
