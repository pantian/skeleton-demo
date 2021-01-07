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
    //Server::onStart
    'start' => [
    ],
    //Server::onWorkerStart
    'workerStart' => [
        [\App\Listens\Pool::class, 'workerStart'],
    ],

	//request 生命周期

	'middleware'=>[
		'requestMiddleware'=>\App\Middleware\RequestMiddleware::class
	]


];
