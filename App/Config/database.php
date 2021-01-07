<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */
return [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'pantian',
    'username' => 'pantian',
    'password' => '6Lrrhbss5Nsd632N',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>false
    ],
    'size' => 10,
];
