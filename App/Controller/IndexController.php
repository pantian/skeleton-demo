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

use Simps\DB\PDO;

class IndexController
{
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
        $name = $data['name'] ?? 'Simps';

        $response->end(
            json_encode(
                [
                    'method' => $request->server['request_method'],
                    'message' => "Hello {$name}.",
                ]
            )
        );
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
