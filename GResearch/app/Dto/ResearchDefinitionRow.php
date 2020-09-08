<?php
namespace App\Dto;

/**
 * アンケート定義（行）クラス
 * GRID/CHECKBOX_GRID などの複数行を保持する定義で使用する
 *
 * @author noshio
 *
 */
class ResearchDefinitionRow
{

	/**
	 * 行タイトル
	 * 定義タイトル + (行タイトル)
	 */
	public $title;

	/**
	 * 識別ID（def_id + row_index）
	 */
	public $identId;

	/**
	 * 選択肢一覧
	 */
	public $items = [];

	/**
	 * コンストラクタ
	 *
	 * @param string $title
	 * @param array $items
	 */
	public function __construct($title, $identId, $items)
	{
		$this->title = $title;
		$this->identId = $identId;
		$this->items = $items;
	}
}

