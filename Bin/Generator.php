<?php
/**
 * 使用 php Generator.php mysqlentity -db xt_content -conf DEV -path=/mnt/hgfs/www/xiaoshenghuo/testPaht
 */


! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
! defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);
! defined('CONFIG_PATH') && define('CONFIG_PATH', BASE_PATH . '/App/Config/');
! defined('APP_PATH') && define('APP_PATH',BASE_PATH . '/App/');



require BASE_PATH."/vendor/autoload.php";

use PTFramework\Config;
use \PTLibrary\Tool\Tool;
use Simps\DB\PDO;
use Simps\DB\Redis;

/**
 * Class Generator
 */
class GeneratorLib {
	/**
	 * GeneratorLib constructor.
	 */
	public static function Generator() {
		global $argv;

//		$cmd = \PTLibrary\Tool\Tool::getArrVal( 1, $argv );
//		if ( ! $cmd ) {
//			self::help();
//		}

		self::help();
		self::builderMysqlEntity();
		self::builderMysqlFactory();

		return ;
		switch ( $cmd ) {
			case 'entity':
				self::builderMysqlEntity();
				break;
			case '-h':
				self::help();
				break;
			case 'factory':
				self::builderMysqlFactory();
				break;

			default:
				self::help();
				break;
		}
	}

	public static function builderMysqlEntity() {
		$db             = \PTLibrary\Tool\Tool::getCliOpt( 'db' );
		$table          = \PTLibrary\Tool\Tool::getCliOpt( 't' );
		$savePath       = \PTLibrary\Tool\Tool::getCliOpt( 'path' );
		$prefix         = \PTLibrary\Tool\Tool::getCliOpt( 'prefix' );
		$postfix        = \PTLibrary\Tool\Tool::getCliOpt( 'postfix' );
		if ( $db ) {

			\PTLibrary\Generator\MysqlEntityBuilder::buildingEntityClass( $db, $table, $savePath, $prefix, $postfix );
		} else {
			self::error( '-db 参数无效' );
		}
	}

	/**
	 * 创建mysql实体类工厂
	 */
	public static function builderMysqlFactory(){
		$db             = Tool::getCliOpt( 'db' );
		$savePath       = Tool::getCliOpt( 'path' );
		$srcPath       =  Tool::getCliOpt( 'src' );

		\PTLibrary\Generator\MysqlFactoryBuilder::buildingFactoryClass( $db, $savePath, $srcPath );
	}

	/**
	 * 显示错误信息
	 *
	 * @param $msg
	 */
	public static function error( $msg ) {
		echo $msg . PHP_EOL . PHP_EOL . PHP_EOL;
		self::help();
		exit( 2 );
	}

	public static function help() {
		echo '-h                显示帮助' . PHP_EOL;
		echo '使用: php Generator.php -db xt_content -conf DEV -path=/mnt/hgfs/www/xiaoshenghuo/testPaht' . PHP_EOL;

		//echo 'entity  生成mysql实体类，参数:  -db: 数据库名，-t:指定表名,如果没有指定，则生成本库下所有的表, -conf : mysql 配置key , -path:生成的文件保存目录, -prefix:类文件前缀, -postfix:类文件后缀',PHP_EOL;
		//echo 'factory 创建mysql实体类工厂 参数:  -db: 数据库名，生成本库下所有的工厂方法, -conf : mysql 配置key , -path:生成的文件保存目录, -prefix:类文件前缀, -postfix:类文件后缀'
		//     , PHP_EOL;
	}
}

\Swoole\Coroutine::create(function (){
		$config = Config::getInstance()->get('database',[]);
		if (! empty($config)) {
			PDO::getInstance($config);
		}
});

\Swoole\Coroutine::create(function (){

	GeneratorLib::Generator();
});
