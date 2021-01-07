<?php
namespace App\Entity\Pantian;

use PTLibrary\DB\MysqlEntity;

class UserEntity extends MysqlEntity {
	public function __construct( $id = null ) {
		$this->_tableName = 'user';
		$this->_dbName = 'pantian';
		parent::__construct( $id );
	}
			
	/**
	 * 
	 * @Type char(20)
	 * @var string  
	 */
	public $id = '';

	/**
	 * 用启
	 * @Type varchar(30)
	 * @var string  
	 */
	public $name = '';

	/**
	 * 城市
	 * @Type varchar(10)
	 * @var string  
	 */
	public $city = '';

	/**
	 * 
	 * @Type varchar(30)
	 * @var string  
	 */
	public $open_id = '';

	/**
	 * 
	 * @Type int
	 * @var int  
	 */
	public $created_at;

    
}