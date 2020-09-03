<?php
namespace App\Dto;

use App\Service\ResearchSummaryService;

/**
 * アンケート定義クラス
 *
 * @author noshio
 *
 */
class ResearchDefinition
{

	/**
	 * アンケートID
	 */
	public $sheet_id;

	/**
	 * アンケート定義ID
	 */
	public $def_id;

	/**
	 * アンケート種別
	 */
	public $data_type;

	/**
	 * アンケートタイトル
	 */
	public $title;

	/**
	 * 選択肢一覧
	 * 行データを保持している場合は $rows 以下で選択肢を保持する
	 */
	public $items = [];

	/**
	 * 行データ
	 * 存在しないアンケート定義の場合は空となる
	 */
	public $rows = [];

	/**
	 * コンストラクタ
	 * データベースより取得した内容から集計用の定義に変換する
	 *
	 * @param \stdClass $def
	 * @param array $def_attr
	 */
	public function __construct($def, $def_attr)
	{
		$this->sheet_id = $def->sheet_id;
		$this->def_id = $def->def_id;
		$this->data_type = $def->data_type;
		$this->title = $def->title;

		if (in_array($def->data_type, ResearchSummaryService::row_data_types, true)) {
			$grouped = collect($def_attr)->groupBy('rows_key')->toArray();

			$index = 0;
			foreach (array_keys($grouped) as $rows_key) {
				$rows_title = null;
				$rows_identId = null;
				$items = [];
				foreach ($grouped[$rows_key] as $rows_item) {
					if ($rows_title == null) {
						$rows_title = $rows_item->rows_title;
					}
					if ($rows_identId == null) {
						$rows_identId = $rows_item->def_id . '_' . $index;
					}
					array_push($items, $rows_item->item);
				}
				array_push($this->rows, new ResearchDefinitionRow($rows_title, $rows_identId, $items));
				$index ++;
			}
		} else {
			foreach ($def_attr as $attr) {
				array_push($this->items, $attr->item);
			}
		}
	}

	/**
	 * 集計対象の定義か判定する
	 *
	 * @return true:集計対象、false:集計対象外
	 */
	public function isSummaryTarget()
	{
		return empty($this->items) ? false : true;
	}
}

