<?php
namespace App\Service;

use App\Dto\ResearchDefinition;
use App\Model\SheetDef;
use Exception;

class SheetDefinitionService
{

	/**
	 * アンケート定義リスト
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
		$defs = SheetDef::getAllDefinitions($sheet_id);
		if (empty($defs)) {
			throw new Exception('アンケート定義が存在しません。');
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
	 * 設定済のアンケート定義IDを取得する
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
	 * sheet_data_detail テーブルへの登録パラメータを生成する
	 *
	 * @param \stdClass $data
	 * @param integer $data_id
	 * @return array
	 */
	public function generateInsParam($data, $data_id)
	{
		$result = [];
		$def = $this->getDef($data->id);

		if (empty($def->rows)) {
			if (is_array($data->value)) {
				foreach ($data->value as $val) {
					array_push($result, [
						'data_id' => $data_id,
						'def_id' => $def->def_id,
						'ident_id' => $def->def_id,
						'value' => $val
					]);
				}
			} else {
				array_push($result, [
					'data_id' => $data_id,
					'def_id' => $def->def_id,
					'ident_id' => $def->def_id,
					'value' => $data->value
				]);
			}
		} else {
			for ($i = 0; $i < count($data->value); $i ++) {

				$def_id = $def->def_id;
				$ident_id = $def->rows[$i]->identId;

				if (is_array($data->value[$i])) {
					foreach ($data->value[$i] as $val) {
						array_push($result, [
							'data_id' => $data_id,
							'def_id' => $def_id,
							'ident_id' => $ident_id,
							'value' => $val
						]);
					}
				} else {
					array_push($result, [
						'data_id' => $data_id,
						'def_id' => $def_id,
						'ident_id' => $ident_id,
						'value' => $data->value[$i]
					]);
				}
			}
		}
		return $result;
	}
}