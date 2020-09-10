<?php
namespace App\Service;

use App\Dto\ResearchDefinition;
use App\Model\SheetDef;
use Illuminate\Support\Facades\DB;
use Exception;

class SheetDataService
{

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
	 *
	 * @param integer $sheet_id
	 * @throws Exception
	 */
	public function __construct($sheet_id)
	{
		$this->sheet_id = $sheet_id;
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

	public function getAnswerList($sheet_datas)
	{
		$result = array();

		$sheet_data_dtls = DB::table('sheet_data_details')->whereIn('data_id', collect($sheet_datas)->pluck('data_id'))
			->orderBy('data_id', 'desc')
			->get();
		foreach ($sheet_data_dtls as $sheet_data_dtl) {
			$record = collect($result)->filter(function ($val, $key) use (&$sheet_data_dtl) {
				return $sheet_data_dtl->data_id == $key;
			});

			if (empty($record) || count($record) == 0) {
				$record[$sheet_data_dtl->ident_id] = $sheet_data_dtl->value;
				$result[$sheet_data_dtl->data_id] = $record;
			} else {
				$result[$sheet_data_dtl->data_id][$sheet_data_dtl->ident_id] = isset($result[$sheet_data_dtl->data_id][$sheet_data_dtl->ident_id]) ? $result[$sheet_data_dtl->data_id][$sheet_data_dtl->ident_id] . ', ' . $sheet_data_dtl->value : $sheet_data_dtl->value;
			}
		}

		return $result;
	}

	/**
	 * アンケート定義IDを取得する
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
}