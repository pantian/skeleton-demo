<?php


namespace App\Controller;


use Swoole\Http\Request;
use Swoole\Http\Response;

class CommonController  extends BaseController {

	public \PTLibrary\Result\Result $result;

	function init( Request $request = null, Response $response = null ) {
		$this->result=$response->resultObject;
	}

}