<?php


namespace App\Dao;


use App\Factory\PantianEntityFactory;
use PTLibrary\Tool\Request;
use PTLibrary\Tool\Tool;

class UserDao {
	/**
	 * @return \App\Entity\Pantian\UserEntity
	 * @throws \PTLibrary\Exception\DBException
	 */
	public static function instance(){
		return PantianEntityFactory::UserEntity();
	}



	public static function add(){
		$start = microtime( true );
	    $entity=self::instance();
		$entity->id = Tool::getRandChar( 20 );
		$entity->created_at=time();
		$entity->open_id = Tool::getRandChar( 28 );
		$entity->name = Tool::getRandChar( 8 );
		$req=Request::instance();
		$sleep=$req->get('time');
		$mod = $entity->getMod();

		$list=$mod->limit(4)->select();

		/*echo $sleep.PHP_EOL;
		$list=$mod->sleep($sleep)->limit(10)->select();
		$mod->beginTransaction();
		$id=$mod->add($entity->getDataToArr());
		$mod->commit();*/
//		print_r( $id . PHP_EOL );


	}

}