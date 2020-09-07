<?php
namespace App\Logging;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;

/**
 * カスタムロガークラス
 *
 * @author noshio
 *
 */
class CustomLogger
{

	/**
	 * ログ出力用日付フォーマット
	 */
	const dateFormat = 'Y/m/d H:i:s.v';

	/**
	 * カスタムMonologインスタンスの生成
	 *
	 * @param array $config
	 * @return \Monolog\Logger
	 */
	public function __invoke(array $config)
	{
		/**
		 * monolog が理解できるlevel表記に変更
		 */
		$level = Logger::toMonologLevel($config['level']);

		/**
		 * ルーティング(ログ出力先)設定
		 */
		$hander = new RotatingFileHandler($config['path'], $config['days'], $level);

		/**
		 * ログのフォーマット指定
		 * ここでは指定(null)しないが、1つ目の引数にログのformatを指定することも可能
		 */
		$hander->setFormatter(new LineFormatter(null, self::dateFormat, true, true));

		/**
		 * ログ生成
		 */
		$logger = new Logger('Custom');
		$logger->pushHandler($hander);
		return $logger;
	}
}

