<?php
namespace App\Entity\Pantian;

use PTLibrary\DB\MysqlEntity;

class Mytab1Entity extends MysqlEntity {
	public function __construct( $id = null ) {
		$this->_tableName = 'mytab1';
		$this->_dbName = 'pantian';
		parent::__construct( $id );
	}
			
	/**
	 * 
	 * @Type int
	 * @var int  
	 */
	public $id = 0;

	/**
	 * 
	 * @Type varchar(100)
	 * @var string  
	 */
	public $name = '';

    
}