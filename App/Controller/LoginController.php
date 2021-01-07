<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */
namespace App\Controller;

use App\Dao\UserDao;
use PTLibrary\Cache\Redis;
use Simps\DB\PDO;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * 登录处理
 * Class LoginController
 *
 * @package App\Controller
 */
class LoginController extends CommonController
{

	public $middleware='';

	public function __construct() {
		parent::__construct();
	}

	function init( Request $request = null, Response $response = null ) {
		parent::init($request,$response);
	}


}
