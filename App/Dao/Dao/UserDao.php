<?php


namespace App\Library\Dao;


use App\Factory\PantianEntityFactory;
use Bin\Tool\Context;
use Bin\Tool\Tool;

class UserDao {
	/**
	 * @return \App\Entity\Pantian\UserEntity
	 * @throws \Bin\Exception\DBException
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
		$entity->getMod()->add( $entity->getDataToArr() );

	}

}