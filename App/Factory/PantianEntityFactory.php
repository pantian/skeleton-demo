<?php

namespace App\Factory;

use PTLibrary\Exception\DBException;
use PTLibrary\Error\ErrorHandler;
use PTLibrary\Factory\EntityFactoryBase;
class PantianEntityFactory extends EntityFactoryBase {
   /**
	* 
	* 			
	* @param mixed $id
	* @return \App\Entity\Pantian\Mytab1Entity
	* @throws \PTLibrary\Exception\DBException
	*/
	public static function Mytab1Entity(){
		$instance=parent::instance(\App\Entity\Pantian\Mytab1Entity::class);
		if(!$instance){
			throw new DBException(ErrorHandler::GET_CONTROL_INSTANCE_EXCEPTION,'\App\Entity\Pantian\Mytab1Entity 生成实例失败');
		}
		return $instance;
	}
   /**
	* 
	* 			
	* @param mixed $id
	* @return \App\Entity\Pantian\UserEntity
	* @throws \PTLibrary\Exception\DBException
	*/
	public static function UserEntity(){
		$instance=parent::instance(\App\Entity\Pantian\UserEntity::class);
		if(!$instance){
			throw new DBException(ErrorHandler::GET_CONTROL_INSTANCE_EXCEPTION,'\App\Entity\Pantian\UserEntity 生成实例失败');
		}
		return $instance;
	}

}