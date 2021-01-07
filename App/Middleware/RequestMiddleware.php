<?php


namespace App\Middleware;


use PTFramework\MiddlewareInterface\RequestMiddlewareInterface;
use PTLibrary\Result\Result;
use PTLibrary\Tool\Tool;
use Swoole\Http\Request;
use Swoole\Http\Response;

class RequestMiddleware implements RequestMiddlewareInterface {
	public static function RequestStart( Request $request, Response $response ) {
		$response->resultObject=Result::Instance();
		$response->resultObject->setRequestId(Tool::getRandChar(9));
		\PTLibrary\Tool\Request::instance( $request );
		\PTLibrary\Tool\Response::instance( $response );

		$response->header( 'Access-Control-Allow-Origin', $request->header['origin']??'*' );
		$response->header( 'Access-Control-Allow-Credentials', 'true' );
		$response->header( 'Content-Type', 'application/json; charset=utf-8' );
	}

	public static function end( Request $request, Response $response,$result=null ) {

		if ( is_array( $result ) ) {
			$response->end( json_encode( $result ) );
		}elseif(is_string($result)){
			$response->end( $result );
		}else if( $result instanceof \Exception){
			$_resObj=Result::Instance();
			$_resObj->setCodeMsg(
				$result->getMessage(),$result->getCode()
			);
			$response->end( (string)$_resObj);
		}else{
			$response->end( (string) $result );
		}
	}


}