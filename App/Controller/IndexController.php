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
 * Class IndexController
 *
 * @package App\Controller
 */
class IndexController extends CommonController
{

	public $middleware='';

	public function __construct() {
		parent::__construct();
	}

	function init( Request $request = null, Response $response = null ) {
		parent::init($request,$response);
	}

	public function index($request, $response)
    {
        $response->end(
            json_encode(
                [
                    'method' => $request->server['request_method'],
                    'message' => 'Hello Simps.',
                ]
            )
        );
    }

    public function hello($request, $response, $data)
    {
    	var_dump(Redis::get('aaa'));
        $name = $data['name'] ?? 'Simps';


    }

    public function test(){
	    Redis::set( 'aaa', 22, 10 );
	    return 'llllllllll';
    }
    public function test2(){
        echo 'test2';
    }

    public function addUser(){
		try{


			UserDao::add();
			return false;
			$pdo = PDO::getInstance()->getConnection();
			$name = uniqid();
			$sql="show full fields from `pantian`.`user`";
			$statement = $pdo->prepare($sql);
			if (!$statement) {
				throw new \Exception('Prepare failed');
			}

			$result = $statement->execute([]);
			$list=$statement->fetchAll();
			echo count($list).PHP_EOL;
			$statement->closeCursor();
			if (!$result) {
				throw new \Exception('Execute failed');
			}


			PDO::getInstance()->close($pdo);
		}catch(Exception $e){
			print_r( $e->getMessage() );
		}


    }

    public function add($request, $response, $data){
	    $pdo = PDO::getInstance()->getConnection();
	    $name = uniqid();
	    $sql="INSERT INTO `pantian`.`mytab1` ( `name`) VALUES (?);";
	    $statement = $pdo->prepare($sql);
	    if (!$statement) {
		    throw new \Exception('Prepare failed');
	    }

	    $result = $statement->execute([$name]);
	    $statement->closeCursor();
	    if (!$result) {
		    throw new \Exception('Execute failed');
	    }
	    $id = $pdo->lastInsertId();

	    PDO::getInstance()->close($pdo);
		return $id;

	    //unset($statement,$result,$pdo);
    }
}
