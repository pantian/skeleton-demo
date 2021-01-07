<?php
/**
 * Created by PhpStorm.
 * User: yons
 * Date: 2018/8/11
 * Time: 16:11
 */


define( 'APP_PATH', __DIR__ );
include_once __DIR__ . '../../Server/HttpServer.php';
include_once __DIR__ . '/AutoLoad.php';
define( 'PROVIDER_CONFIG_KEY', 'sql_update' );
$conf = \Tool\Tool::getCliOpt( 'conf' );
if($conf){
	define( 'CONF_KEY', $conf );
}else{
	define( 'CONF_KEY', 'VOTE' );

}


/**
 * 数据库更新
 *
 *
 * Class sql_upload
 */
class sql_upload {

	public static $version = [];

	public static function help() {
		echo "-------数据库更新帮助-------\n";
		echo "使用：php sql_upload.php -v 版本号 -conf 数据库配置KEY \n\n";
	}

	public static function showErrorMsg( $msg = '提示' ) {
		echo "\e[41m\e[1m {$msg} \e[0m", PHP_EOL;
	}

	public static function showSuccessMsg( $msg = 'OK' ) {
		echo "\e[32m\e[1m {$msg} \e[0m", PHP_EOL;
	}

	/**
	 *
	 */
	public static function DoUpdate() {
		global $argv;
		$cmd = \Tool\Tool::getArrVal( 1, $argv );
		if ( ! $cmd ) {
			self::help();
		}
		echo "执行.....\n";
		$v = \Tool\Tool::getCliOpt( 'v' );

		if ( ! $v ) {
			self::showErrorMsg( '错误:请输入版本号' );

			return;
		}
		if ( ! isset( self::$version[ $v ] ) ) {
			self::showErrorMsg( '错误:版本号不存在' );

			return;
		}
		$sqlDataArr = self::$version[ $v ];
		foreach ( $sqlDataArr as $type => $params ) {
			if ( empty( $params ) ) {
				continue;
			}
			try {
				switch ( $type ) {
					case 'add_filed':
						foreach ( $params as $p ) {
							self::addField( $p );
						}

						break;
					case 'del':
						foreach ( $params as $p ) {
							self::delField( $p );
						}
						break;
					case 'create_table':
						foreach ( $params as $p ) {
							self::createTable( $p );
						}
						break;
					case 'del_table':
						foreach ( $params as $p ) {
							self::delTable( $p );
						}
						break;
					case 'index':
						foreach ( $params as $p ) {
							try{
								self::createIndex( $p );
							}catch(Exception $e){
								self::showErrorMsg( $e->getMessage() );
							}
						}
						break;
					case 'del_index':
						foreach ( $params as $p ) {
							try{
								self::delIndex( $p );
							}catch(Exception $e){
								self::showErrorMsg( $e->getMessage() );
							}
						}
						break;


				}
			} catch ( Exception $e ) {
				self::showErrorMsg( $e->getMessage() );
			}
		}
	}

	/**
	 * 建表
	 *
	 * @param $sqlParams
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function createTable( $sqlParams ) {
		$mod = new \DB\BaseM();
		$db  = $sqlParams['db'];
		if ( ! $db ) {
			self::showErrorMsg( '没有指定数据库' );

			return false;
		}
		$mod->setDBName( $db );
		$sql = \Tool\Tool::getArrVal( 'sql', $sqlParams );
		if ( ! $sql ) {
			throw new Exception( '创建表的sql内容无效，必须是一个完整无误的create table 语句' );
		}
		if ( ! $mod->query( $sql ) ) {
			echo $sql, PHP_EOL;
			throw new Exception( '创建表失败' );
		} else {
			self::showSuccessMsg( '创建表成功' );
		}

	}

	/**
	 * 删除表
	 *
	 * @param $sqlParams
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function delTable( $sqlParams ) {
		$mod   = self::getMod( $sqlParams );
		$table = \Tool\Tool::getArrVal( 'table', $sqlParams );
		$sql   = 'DROP TABLE ' . $table;
		if ( ! $mod->query( $sql ) ) {
			self::showErrorMsg( '删除表失败' );
			echo $sql, PHP_EOL;
		} else {
			self::showSuccessMsg( '删除表成功' );
		}

	}

	/**
	 * @param $sqlParams
	 *
	 * @throws \Exception\DBException
	 */
	public static function delIndex( $sqlParams ) {
		$mod        = self::getMod( $sqlParams );
		$indexNames = \Tool\Tool::getArrVal( 'indexName', $sqlParams );
		if ( $indexNames ) {
			foreach ( $indexNames as $index ) {
				try{
					echo '删除索引：' . $index,PHP_EOL;
					$mod->delIndex( $index ) ;
					self::showSuccessMsg( "删除索引:\t" . $index );
				}catch(Exception $e){
					self::showErrorMsg( $e->getMessage() );
				}
			}
		}
	}

	/**
	 * 添加字段
	 *
	 * @param $sqlParams
	 *
	 * @throws \Exception\DBException
	 */
	public static function addField( $sqlParams ) {
		//print_r( $sqlParams );
		$mod    = self::getMod( $sqlParams );
		$params = \DB\DbFiledParam::instance( false );
		$fData  = $sqlParams['filed_params'];
		if ( ! $fData ) {
			throw new Exception( '缺少filed_params字段' );
		}
		$params->field     = \Tool\Tool::getArrVal( 'field', $fData );
		$params->type      = \Tool\Tool::getArrVal( 'type', $fData );
		$params->length    = \Tool\Tool::getArrVal( 'length', $fData );
		$params->default   = \Tool\Tool::getArrVal( 'default', $fData );
		$params->charset   = \Tool\Tool::getArrVal( 'charset', $fData, 'utf8' );
		$params->is_null   = \Tool\Tool::getArrVal( 'is_null', $fData );
		$params->comment   = \Tool\Tool::getArrVal( 'comment', $fData );
		$params->point     = \Tool\Tool::getArrVal( 'point', $fData );
		$params->new_field = \Tool\Tool::getArrVal( 'new_field', $fData );

		if($params->new_field){
			$res = $mod->changeField( $params );
			if ( $res === false ) {
				self::showErrorMsg( '添加字段失败:' . $params->field );
			} else {
				self::showSuccessMsg( $params->field . "\t\t OK" );
			}
		}else{
			$res = $mod->addField( $params );
			if ( $res === false ) {
				self::showErrorMsg( '修改字段失败:' . $params->new_field );
			} else {
				self::showSuccessMsg( $params->field . "\t\t OK" );
			}
		}

	}

	/**
	 * @param $sqlParams
	 *
	 * @return \DB\BaseM
	 * @throws \Exception\DBException
	 */
	public static function getMod( $sqlParams ) {
		$mod   = new \DB\BaseM();
		$db    = $sqlParams['db'];
		$table = $sqlParams['table'];
		$mod->setDBName( $db );
		$mod->setTable( $table );

		return $mod;
	}

	/**
	 * @param $sqlParams
	 *
	 * @throws \Exception\DBException
	 */
	public static function createIndex( $sqlParams ) {
		$mod                    = self::getMod( $sqlParams );
		$params                 = \Tool\Tool::getArrVal( 'params', $sqlParams );
		foreach ( $params as $param ) {
			$indexParams            = \DB\DbIndexParam::instance();
			$indexParams->indexName = \Tool\Tool::getArrVal( 'indexName', $param );
			$indexParams->filed     = \Tool\Tool::getArrVal( 'filed', $param );
			$indexParams->type      = \Tool\Tool::getArrVal( 'type', $param );
			$indexParams->comment   = \Tool\Tool::getArrVal( 'comment', $param );
			try{
				if ( $mod->createIndex( $indexParams ) ) {
					self::showSuccessMsg( "创建索引成功\t" . $indexParams->indexName );
				}
			}catch(Exception $e){

				self::showErrorMsg( "创建索引失败\t" . $e->getMessage());
			}

		}
	}

	/**
	 * @param $sqlParams
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function check( $sqlParams ) {
		$db    = $sqlParams['db'];
		$table = $sqlParams['table'];
		if ( ! $db ) {
			throw new Exception( '没有指定数据库' );
		}
		if ( ! $table ) {
			throw new Exception( '没有指定表名' );

		}

		return true;
	}

	/**
	 * @param $sqlParams
	 *
	 * @return bool
	 * @throws \Exception\DBException
	 * @throws \Exception
	 */
	public static function delField( $sqlParams ) {
		$mod   = self::getMod( $sqlParams );
		$fData = $sqlParams['filed_params'];
		if ( ! $fData ) {
			throw new Exception( '缺少filed_params字段' );

		}

		$filed = \Tool\Tool::getArrVal( 'filed', $fData );
		if ( ! $filed ) {
			throw new Exception( '缺少filed字段无效' );

		}
		$res = $mod->delField( $filed );
		if ( $res === false ) {
			throw new Exception( '删除字段失败:' . $filed );
		} else {
			throw new Exception( '删除 ' . $filed . "\t\t OK" );
		}
	}


}


sql_upload::$version['2.4'] = [
	'add_filed'          => [
		[
			'db'           => 'toupiao',
			'table'        => 'gh',
			'filed_params' => [
				'filed'     => 'test_max',
				'type'      => 'int',
				'length'    => 5,
				'default'   => 0,
				'point'     => 0,
				'is_null'   => false,
				'comment'   => '最长',
				'charset'   => 'uft8',
				'new_field' => '',
			],
		],
		[
			'db'           => 'toupiao',
			'table'        => 'player',
			'filed_params' => [
				'filed'   => 'test_max',
				'type'    => 'int',
				'length'  => 5,
				'default' => 0,
				'point'   => 0,
				'is_null' => false,
				'comment' => '最长2',
				'charset' => 'uft8',
			],
		],

	]
];
sql_upload::$version['2.5'] = [

	'del'          => [
		[
			'db'           => 'toupiao',
			'table'        => 'player',
			'filed_params' => [
				'filed' => 'test_max',
			],
		],
		[
			'db'           => 'toupiao',
			'table'        => 'gh',
			'filed_params' => [
				'filed' => 'test_max',
			],
		],

	],
	'create_table' => [

	],
	'del_table'    => [

	],
];

sql_upload::$version['2.6'] = [
	'create_table' => [
		[
			'db'  => 'toupiao',
			'sql' => "CREATE TABLE `minute_static1` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `number` int(11) DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`player_id`,`time`),
  KEY `player_id` (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='选手每分钟票数统计';
",
		],
	],
	'del_table'    => [

	],
];
sql_upload::$version['2.7'] = [
	'del_table' => [
		[
			'db'    => 'toupiao',
			'table' => 'minute_static1',
		],
	],
];


sql_upload::$version['2.8'] = [
	'index' => [
		[
			'db'     => 'toupiao',
			'table'  => 'browse_history',
			'params' => [
				[
				'indexName' => 'uid',
				'filed'     => 'uid',
				'type'      => \DB\DbIndexParam::TYPE_INDEX,
				'comment'   => 'test'
				],
				[
					'indexName' => 'object_id',
					'filed'     => 'object_id',
					'type'      => \DB\DbIndexParam::TYPE_INDEX,
					'comment'   => 'test',
				]

			],
		],
	],
];
sql_upload::$version['2.9'] = [
	'del_index' => [
		[
			'db'        => 'toupiao',
			'table'     => 'browse_history',
			'indexName' => [ 'ip','object_id_2','type_2','uid','created_2' ]
		],
	],
];
//时分票数统计，自动禁止
sql_upload::$version['3.0'] = [
	'create_table' => [
		[
			'db'  => 'toupiao',
			'sql' => "CREATE TABLE `minute_static` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `number` int(11) DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`player_id`,`time`),
  KEY `player_id` (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='选手每分钟票数统计';
",
		],
	],
	'add_filed'          => [
		[
			'db'           => 'toupiao',
			'table'        => 'gh',
			'filed_params' => [
				'filed'     => 'minuter_max',
				'type'      => 'int',
				'length'    => 5,
				'default'   => 0,
				'point'     => 0,
				'is_null'   => false,
				'comment'   => '选手每分钟时间最多票数，超过自动被禁止',
				'charset'   => 'uft8',
				'new_field' => '',
			],
		]
	],

];

//修改字段名为minute_max
sql_upload::$version['3.1'] = [

	'add_filed'          => [
		[
			'db'           => 'toupiao',
			'table'        => 'gh',
			'filed_params' => [
				'field'     => 'minuter_max',
				'type'      => 'int',
				'length'    => 5,
				'default'   => 0,
				'point'     => 0,
				'is_null'   => false,
				'comment'   => '选手每分钟时间最多票数，超过自动被禁止',
				'charset'   => 'uft8',
				'new_field' => 'minute_max',
			],
		]
	],

];
global $SqlVersion;
sql_upload::$version=$SqlVersion;
sql_upload::DoUpdate();
