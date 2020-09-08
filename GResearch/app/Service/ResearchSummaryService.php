<?php
namespace App\Service;

use App\Dto\ResearchDefinition;
use App\Dto\ResearchDefinitionRow;
use App\Model\SheetData;
use App\Model\SheetDef;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * アンケート集計サービスクラス
 *
 * @author noshio
 *
 */
class ResearchSummaryService
{

	/**
	 * 行が存在するアンケート定義のデータタイプ
	 */
	const row_data_types = [
		'GRID',
		'CHECKBOX_GRID'
	];

	/**
	 * シートID
	 */
	private $sheet_id;

	/**
	 * 集計対象のアンケート定義リスト
	 */
	private $defs = [];

	/**
	 * コンストラクタ
	 * sheet_id よりアンケート定義を取得し初期化を行う
	 *
	 * @param int $sheet_id
	 * @throws Exception
	 */
	public function __construct($sheet_id)
	{
		$this->sheet_id = $sheet_id;
		$defs = SheetDef::getResearchDefinitions($sheet_id);
		if (empty($defs)) {
			throw new Exception('集計対象となるアンケート定義が存在しません。');
		}

		foreach ($defs as $def) {
			if (empty($this->defs) == false && in_array($def->def_id, $this->getDefIds())) {
				continue;
			}
			$def_attr = collect($defs)->where('def_id', $def->def_id);
			array_push($this->defs, new ResearchDefinition($def, $def_attr));
		}
	}

	/**
	 * 集計対象設定済のアンケート定義IDを取得する
	 *
	 * @return array
	 */
	public function getDefIds()
	{
		$result = [];
		if (empty($this->defs) == false) {
			foreach ($this->defs as $def) {
				array_push($result, $def->def_id);
			}
		}
		return $result;
	}

	/**
	 * 識別IDをカンマ区切りで返却する
	 *
	 * @return string
	 */
	public function getIdentIds()
	{
		$titles = $this->getTitleArray();
		$tmp = collect($titles)->pluck('id')->all();
		return $tmp;
	}

	/**
	 * def_id に一致するアンケート定義を返却する
	 * 指定がない場合には、保持しているアンケート定義全てを配列で返却する
	 *
	 * @param int $def_id
	 * @return NULL|array|ResearchDefinition
	 */
	public function getDef($def_id = null)
	{
		$result = null;
		if ($def_id == null) {
			$result = $this->defs;
		} else {
			foreach ($this->defs as $def) {
				if ($def->def_id == $def_id) {
					$result = $def;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * def_id に一致する質問タイトルを返却する
	 * 指定がない場合には、保持している質問タイトル全てを返却する
	 *
	 * @param int $def_id
	 * @return array
	 */
	public function getTitleArray($def_id = null)
	{
		$result = [];
		foreach ($this->defs as $def) {
			if ($def_id == null) {
				if (empty($def->rows)) {
					array_push($result, array(
						'title' => $def->title,
						'id' => $def->def_id
					));
				} else {
					collect($def->rows)->each(function ($row, $key) use (&$result, &$def) {
						array_push($result, array(
							'title' => $row->title,
							'id' => $def->def_id . '_' . $key
						));
					});
				}
			} else {
				if ($def->def_id == $def_id) {
					if (empty($def->rows)) {
						array_push($result, array(
							'title' => $def->title,
							'id' => $def->def_id
						));
					} else {
						collect($def->rows)->each(function ($row, $key) use (&$result, &$def) {
							array_push($result, array(
								'title' => $row->title,
								'id' => $def->def_id . '_' . $key
							));
						});
					}
				}
			}
		}
		return $result;
	}

	/**
	 * 識別IDから定義情報を取得して返却する
	 *
	 * @param string $ident_id
	 * @return ResearchDefinition|ResearchDefinitionRow
	 */
	public function getIdentDefinition($ident_id)
	{
		$result = null;
		foreach ($this->defs as $def) {
			if (strcmp($ident_id, $def->def_id) == 0) {
				$result = $def;
				break;
			}
			if (empty($def->rows) == false) {
				$def_rows = [];
				collect($def->rows)->each(function ($row) use (&$ident_id, &$def_rows) {
					if (strcmp($row->identId, $ident_id) == 0) {
						array_push($def_rows, $row);
					}
				});

				if (count($def_rows) > 0) {
					$result = $def_rows[0];
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * データ行に除外対象の回答が存在するか判定する
	 *
	 * @param array $row
	 * @param array $params
	 * @return boolean
	 */
	public function isExcludeRow($row, $params)
	{
		$result = false;
		if (is_null($params) || empty($params[0])) {
			return false;
		}

		foreach ($params as $param) {
			$key = explode('_', str_replace('def_id_', '', $param));
			$def = $this->getDef($key[0]);

			$target = collect($row)->where('id', $key[0])->first();
			if (is_null($def) || empty($target)) {
				continue;
			}

			$value = [];
			if (count($key) > 2) {
				$explodAnswer = $def->rows[$key[1]]->items[$key[2]];
				$value = $target['value'][$key[1]];
			} else {
				$explodAnswer = $def->items[$key[1]];
				$value = $target['value'];
			}

			switch ($def->data_type) {
				case 'CHECKBOX':
				case 'CHECKBOX_GRID':
					if (in_array($explodAnswer, $value)) {
						$result = true;
						break;
					}
				default:
					if ($explodAnswer == $value) {
						$result = true;
						break;
					}
			}
		}
		return $result;
	}

	/**
	 * アンケート識別IDと答えのペアを返却する
	 *
	 * @param array $params
	 * @return array
	 */
	public function getIdentPair($params)
	{
		$result = [];

		if (is_null($params) || empty($params[0])) {
			return $result;
		}

		foreach ($params as $param) {
			$key = explode('_', str_replace('def_id_', '', $param));
			$def = $this->getDef($key[0]);

			$ident_id = null;
			$answer = null;
			if (count($key) > 2) {
				$ident_id = $key[0] . '_' . $key[1];
				$answer = $def->rows[$key[1]]->items[$key[2]];
			} else {
				$ident_id = $key[0];
				$answer = $def->items[$key[1]];
			}

			array_push($result, [
				'key' => $ident_id,
				'val' => $answer
			]);
		}
		return $result;
	}

	/**
	 * 集計データオブジェクトを生成して返却する
	 *
	 * @param integer $ident_id
	 * @param array $params
	 * @return array
	 */
	public function getSummaryData($ident_id, $params = null)
	{
		$result = [];
		$exclude_pair = $this->getIdentPair($params);

		/**
		 * アンケート回答データを取得しオブジェクト変換する
		 */
		$sql = SheetData::getSummary($this->sheet_id, $ident_id, $exclude_pair);
		$rows = DB::select($sql);

		/**
		 * 集計オブジェクト生成
		 */
		try {
			$ident_def = $this->getIdentDefinition($ident_id);
		} catch (\Exception $e) {
			Log::critical('[ident_id:' . $rows[0]->ident_id . '] [詳細:' . $e->getTraceAsString() . ']');
		}

		$title = $ident_def->title;
		$labels = [];
		$datas = [];

		foreach ($ident_def->items as $item) {
			try {
				array_push($labels, $item);
				$data = [];
				collect($rows)->each(function ($row) use (&$item, &$data) {
					if ($row->value == $item) {
						array_push($data, $row);
					}
				});
				array_push($datas, (count($data) > 0 ? $data[0]->data_count : 0));
			} catch (\Exception $e) {
				Log::critical('[ident_id:' . $rows[0]->ident_id . '] [詳細:' . $e->getTraceAsString() . ']');
			}
		}

		$groupData = [
			'title' => $title,
			'identId' => $ident_id,
			'labels' => $labels,
			'datas' => $datas
		];
		array_push($result, $groupData);

		return $result;
	}

	/**
	 *
	 * @param string $ident_id
	 * @param string $cross_id
	 * @param array $params
	 * @return array
	 */
	public function getCrossSummaryData($ident_id, $cross_id, $params = null)
	{
		$result = [];
		$exclude_pair = $this->getIdentPair($params);
		$cross_id = str_replace('check_item_', '', $cross_id);

		/**
		 * アンケート回答データを取得しオブジェクト変換する
		 */
		$sql = SheetData::getCrossSummary($this->sheet_id, $ident_id, $cross_id, $exclude_pair);
		$rows = DB::select($sql);

		/**
		 * クロス集計用定義情報の取得
		 */
		try {
			$identDef = $this->getIdentDefinition($ident_id);
			$crossDef = $this->getIdentDefinition($cross_id);
		} catch (\Exception $e) {
			Log::critical('[ident_id:' . $rows[0]->ident_id . '] [詳細:' . $e->getTraceAsString() . ']');
		}

		$title = $identDef->title;
		$labels = collect($identDef->items)->toArray();
		$data_labels = [];
		$datas = [];

		foreach ($crossDef->items as $crossItem) {
			try {
				$data = [];
				array_push($data_labels, $crossItem);
				foreach ($identDef->items as $item) {
					$record = collect($rows)->filter(function ($row) use (&$item, &$data, &$crossItem) {
						return $row->value == $item && $row->cross_value == $crossItem;
					});

					array_push($data, (count($record) > 0 ? collect($record)->first()->counts : 0));
				}
				array_push($datas, $data);
			} catch (\Exception $e) {
				Log::critical('[ident_id:' . $rows[0]->ident_id . '] [詳細:' . $e->getTraceAsString() . ']');
			}
		}

		$groupData = [
			'title' => $title,
			'identId' => $ident_id,
			'labels' => $labels,
			'data_labels' => $data_labels,
			'datas' => $datas
		];
		array_push($result, $groupData);

		return $result;
	}

	/**
	 * 回答一覧より集計を行い結果を返却する
	 * def_id 指定の場合、対象定義情報のみを取得する
	 * パフォーマンスが出ないため廃止予定
	 *
	 * @param array $elements
	 * @param int $def_id
	 * @return array
	 */
	public function generateGroupSummary(&$elements, $def_id = null)
	{
		Log::info('create def_id grouping start.');
		$groupElements = collect($elements)->groupBy('id');
		Log::info('create def_id grouping finish.');

		$result = [];

		Log::info('generate group summary start.');
		foreach ($this->defs as $def) {

			/**
			 * def_id 指定リクエストの場合、一致しないものは処理対象外とする
			 */
			if ($def_id != null) {
				if ($def->def_id != $def_id) {
					continue;
				}
			}

			/**
			 * 対象の回答のみ取得する
			 */
			if (isset($groupElements[$def->def_id]) == false) {
				continue;
			}
			$targetElms = $groupElements[$def->def_id];

			$title = null;
			$identId = null;
			$labels = [];
			$datas = [];

			/**
			 * data_type ごとに固有の集計処理を行う
			 */
			switch ($def->data_type) {
				case 'CHECKBOX':
					collect(array_values($def->items))->each(function ($item) use (&$def, &$targetElms, &$title, &$identId, &$labels, &$datas) {
						$data = count(collect($targetElms)->filter(function ($val) use ($item) {
							return in_array($item, $val['value'], true);
						}));
						$title = $def->title;
						$identId = $def->def_id;
						array_push($labels, $item);
						array_push($datas, $data);
					});
					break;

				case 'GRID':
					for ($i = 0; $i < count($def->rows); $i ++) {
						$targetAnswers = [];
						foreach ($targetElms as $targetElm) {
							array_push($targetAnswers, $targetElm['value'][$i]);
						}

						$title = $def->rows[$i]->title;
						$identId = $def->rows[$i]->identId;

						foreach (array_values($def->rows[$i]->items) as $item) {
							$data = count(collect($targetAnswers)->filter(function ($answer) use (&$item) {
								return $answer == $item;
							}));
							array_push($labels, $item);
							array_push($datas, $data);
						}

						if (empty($labels) == false && empty($datas) == false) {
							$groupData = [
								'title' => $title,
								'identId' => $identId,
								'labels' => $labels,
								'datas' => $datas
							];
							array_push($result, $groupData);
						}

						$title = null;
						$labels = [];
						$datas = [];
					}
					break;

				case 'CHECKBOX_GRID':
					for ($i = 0; $i < count($def->rows); $i ++) {
						$targetAnswers = [];
						foreach ($targetElms as $targetElm) {
							array_push($targetAnswers, $targetElm['value'][$i]);
						}

						$title = $def->rows[$i]->title;
						$identId = $def->rows[$i]->identId;

						foreach (array_values($def->rows[$i]->items) as $item) {
							$data = count(collect($targetAnswers)->filter(function ($answer) use (&$item) {
								return empty($answer) ? false : in_array($item, $answer);
							}));
							array_push($labels, $item);
							array_push($datas, $data);
						}

						if (empty($labels) == false && empty($datas) == false) {
							$groupData = [
								'title' => $title,
								'identId' => $identId,
								'labels' => $labels,
								'datas' => $datas
							];
							array_push($result, $groupData);
						}

						$title = null;
						$labels = [];
						$datas = [];
					}
					break;

				default:
					collect(array_values($def->items))->each(function ($item) use (&$def, &$targetElms, &$title, &$identId, &$labels, &$datas) {
						$data = count(collect($targetElms)->filter(function ($val) use ($item) {
							return $item == $val['value'];
						}));
						$title = $def->title;
						$identId = $def->def_id;
						array_push($labels, $item);
						array_push($datas, $data);
					});
			}

			if (empty($labels) == false && empty($datas) == false) {
				$groupData = [
					'title' => $title,
					'identId' => $identId,
					'labels' => $labels,
					'datas' => $datas
				];
				array_push($result, $groupData);
			}
		}
		Log::info('generate group summary finish.');

		return $result;
	}
}

