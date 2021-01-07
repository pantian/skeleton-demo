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

	'GET'=>[
		['/', '\App\Controller\IndexController@index']
	],

	['GET','/add','\App\Controller\IndexController@add'],

	'GROUP'=>[
		'/index'=>[
			'GET'=>[
				['/myInfo', '\App\Controller\IndexController@index']
			],
			'POST'=>[
				['/save','\App\Controller\IndexController@save']
			],
			['GET','/add','\App\Controller\IndexController@add'],
		]
	],

    ['GET', '/favicon.ico', function ($request, $response) {
        $response->end('');
    }],
];
