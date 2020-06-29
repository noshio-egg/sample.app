<?php
namespace App;

/**
 * 画面表示メッセージ
 *
 * @author noshio
 *
 */
class Message
{

	/**
	 * メッセージ種別(1:INFO, 2:WARNING, 3:ERROR)
	 */
	public $type;

	/**
	 * メッセージ
	 */
	public $message;

	/**
	 * コンストラクタ
	 *
	 * @param int $type
	 * @param string $message
	 */
	public function __construct($type, $message)
	{
		$this->type = $type;
		$this->message = $message;
	}
}