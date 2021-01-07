<?php


namespace App\Controller;

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * 公共基础控制器
 * Class BaseController
 *
 * @package App\Controller
 */
abstract class BaseController {
	public function __construct() {
	}

	abstract function init(Request $request=null,Response $response=null);
}