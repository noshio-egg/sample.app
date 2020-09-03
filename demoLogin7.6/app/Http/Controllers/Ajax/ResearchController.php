<?php
namespace App\Http\Controllers\Ajax;

use App\SheetData;
use App\Http\Controllers\Controller;
use App\Service\ResearchSummaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResearchController extends Controller
{

	/**
	 * 質問一覧ページ
	 *
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function sample()
	{
		$sheet_id = 2;
		$service = new ResearchSummaryService($sheet_id);
		return view('research.sample')->with([
			'groups' => $service->getDef(),
			'titles' => $service->getTitleArray(),
			'defIds' => $service->getIdentIds()
		]);
	}

	/**
	 * 質問詳細ページ
	 *
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function sampleDetail(Request $request)
	{
		$sheet_id = 2;
		$params = $request->input('param');
		$ident_id = $request->input('ident_id');

		$service = new ResearchSummaryService($sheet_id);
		$summary = $service->getSummaryData($ident_id, $params);

		$ident_pair = collect($service->getTitleArray())->where('id', $ident_id)->first();

		return view('research.detail')->with([
			'summary' => $summary,
			'groups' => $service->getDef(),
			'identId' => $ident_id,
			'detail_title' => $ident_pair['title']
		]);
	}

	/**
	 * 集計データ生成
	 * sheet_rows テーブル参照ケース
	 *
	 * @param Request $request
	 * @return array
	 */
	public function getData2(Request $request)
	{
		$param = $request->input('param');
		$def_id = $request->input('def_id');

		/**
		 * 集計サービス生成
		 */
		Log::info('create research summary service start.');
		$sheet_id = 2;
		$service = new ResearchSummaryService($sheet_id);
		Log::info('create research summary service finish.');

		/**
		 * アンケート回答データを取得しオブジェクト変換する
		 */
		Log::info('get data for database start.');
		$rows = [];
		foreach (DB::table('sheet_rows')->where('sheet_id', 1)
			->orderBy('row_id')
			->limit(10000)
			->cursor() as $record) {
			$dataRow = json_decode($record->data, true);
			// if ($service->isExcludeRow($dataRow, $param) == false) {
			// array_push($rows, $dataRow);
			// }
			array_push($rows, $dataRow);
		}
		Log::info('get data for database finish.');

		Log::info('Filter process : isExcludeRow start.rows count is [' . count($rows) . ']');
		$rows = collect($rows)->filter(function ($value, $key) use ($service, $param) {
			return $service->isExcludeRow($value, $param) == false;
		});
		Log::info('Filter process : isExcludeRow finish.rows count is [' . count($rows) . ']');

		/**
		 * 回答データ成型処理
		 */
		Log::info('create elms start.');
		$elms = [];
		foreach ($rows as $row) {
			collect($row)->each(function ($elm) use (&$elms) {
				array_push($elms, $elm);
			});
		}
		Log::info('create elms finish.');

		/**
		 * 集計結果を返却する
		 */
		return $service->generateGroupSummary($elms, $def_id);
	}

	/**
	 * 集計データ生成
	 * sheet_data_details テーブル参照ケース
	 *
	 * @param Request $request
	 * @return array
	 */
	public function getData3(Request $request)
	{
		$result = [];

		$sheet_id = 2;
		$params = $request->input('param');
		$ident_id = $request->input('ident_id');

		/**
		 * 集計サービス生成
		 */
		$service = new ResearchSummaryService($sheet_id);

		/**
		 * 集計データ生成
		 */
		$result = $service->getSummaryData($ident_id, $params);

		/**
		 * 集計結果を返却する
		 */
		return $result;
	}

	/**
	 * クロス集計対応集計データ生成
	 * sheet_data_details テーブル参照ケース
	 *
	 * @param Request $request
	 * @return array
	 */
	public function getDetailData(Request $request)
	{
		$result = [];

		$sheet_id = 2;
		$params = $request->input('param');
		$ident_id = $request->input('ident_id');
		$cross_id = $request->input('cross_id');

		/**
		 * 集計サービス生成
		 */
		$service = new ResearchSummaryService($sheet_id);

		/**
		 * クロス集計データ生成
		 */
		$result = $service->getCrossSummaryData($ident_id, $cross_id, $params);

		/**
		 * 集計結果を返却する
		 */
		return $result;
	}

/**
 * 集計オブジェクトの配列を生成して返却する
 *
 * @param integer $sheet_id
 * @param integer $ident_id
 * @param array $params
 * @return array
 */
	// private function getSummaryObject($sheet_id, $ident_id, $params = null)
	// {
	// $result = [];

	// /**
	// * 集計サービス生成
	// */
	// Log::info('create research summary service start.');
	// $service = new ResearchSummaryService($sheet_id);
	// Log::info('create research summary service finish.');

	// $exclude_pair = $service->getIdentPair($params);

	// /**
	// * アンケート回答データを取得しオブジェクト変換する
	// */
	// Log::info('get data for database start.');
	// $sql = SheetData::getSummary($ident_id, $exclude_pair);
	// $rows = DB::select($sql);
	// Log::info('get data for database finish.');

	// // TODO: レコードが存在しないケースを考慮すること。

	// /**
	// * 集計オブジェクト生成
	// */
	// try {
	// $ident_def = $service->getIdentDefinition($ident_id);
	// } catch (\Exception $e) {
	// Log::critical('[ident_id:' . $rows[0]->ident_id . '] [詳細:' . $e->getTraceAsString() . ']');
	// }

	// $title = $ident_def->title;
	// $labels = [];
	// $datas = [];

	// foreach ($ident_def->items as $item) {
	// try {
	// array_push($labels, $item);
	// $data = [];
	// collect($rows)->each(function ($row) use (&$item, &$data) {
	// if ($row->value == $item) {
	// array_push($data, $row);
	// }
	// });
	// array_push($datas, (count($data) > 0 ? $data[0]->data_count : 0));
	// } catch (\Exception $e) {
	// Log::critical('[ident_id:' . $rows[0]->ident_id . '] [詳細:' . $e->getTraceAsString() . ']');
	// }
	// }

	// $groupData = [
	// 'title' => $title,
	// 'identId' => $ident_id,
	// 'labels' => $labels,
	// 'datas' => $datas
	// ];
	// array_push($result, $groupData);

	// return $result;
	// }

	// public function getData()
	// {
	// // get answer record and json decord.
	// Log::info('get data for database start.');
	// $rows = [];
	// foreach (DB::table('sheet_rows')->where('sheet_id', 1)
	// ->orderBy('row_id')
	// ->cursor() as $record) {
	// array_push($rows, json_decode($record->data, true));
	// }
	// Log::info('get data for database finish.');

	// return null;

	// $defs = DB::select('
	// select
	// t1.def_id
	// ,t1.data_type
	// ,t2.value as rows_key
	// ,case
	// when t2.value is null then t1.title
	// else concat(t1.title, \'(\', t2.value, \')\')
	// end as title
	// ,t3.value as item
	// from
	// sheet_defs t1
	// left outer join
	// sheet_def_attrs t2
	// on
	// t1.sheet_id = t2.sheet_id
	// and t1.def_id = t2.def_id
	// and t2.key = \'ROW\'
	// inner join
	// sheet_def_attrs t3
	// on
	// t1.sheet_id = t3.sheet_id
	// and t1.def_id = t3.def_id
	// and t3.key = \'LIST\'
	// order by
	// t1.index
	// ,t2.id
	// ,t3.id');

	// $summary = array();
	// collect($defs)->each(function ($def) use (&$summary, $defs) {
	// if (empty($summary) == false && collect(array_keys($summary))->contains($def->def_id)) {
	// if ($def->data_type == 'GRID' || $def->data_type == 'CHECKBOX_GRID') {

	// /**
	// * def_id が存在している場合、GRID系の選択肢のみ追加処理を行う
	// */
	// if (collect($summary[$def->def_id]['rows'])->contains('title', $def->title) == false) {
	// $items = [];
	// collect($defs)->where('def_id', $def->def_id)
	// ->where('rows_key', $def->rows_key)
	// ->each(function ($rec) use (&$items) {
	// $items[$rec->item] = 0;
	// });

	// array_push($summary[$def->def_id]['rows'], array(
	// 'def_id' => $def->def_id,
	// 'title' => $def->title,
	// 'data_type' => $def->data_type,
	// 'items' => $items
	// ));
	// }
	// }
	// } else {
	// /**
	// * GRID系アンケートの場合、rows_key ごとに集計用オブジェクトを生成する
	// */
	// if ($def->data_type == 'GRID' || $def->data_type == 'CHECKBOX_GRID') {
	// $items = [];
	// collect($defs)->where('def_id', $def->def_id)
	// ->where('rows_key', $def->rows_key)
	// ->each(function ($rec) use (&$items) {
	// $items[$rec->item] = 0;
	// });

	// $summary[$def->def_id] = array(
	// 'def_id' => $def->def_id,
	// 'data_type' => $def->data_type,
	// 'rows' => [
	// array(
	// 'def_id' => $def->def_id,
	// 'title' => $def->title,
	// 'data_type' => $def->data_type,
	// 'items' => $items
	// )
	// ]
	// );
	// } else {
	// $items = [];
	// collect($defs)->where('def_id', $def->def_id)
	// ->each(function ($rec) use (&$items) {
	// $items[$rec->item] = 0;
	// });

	// $summary[$def->def_id] = array(
	// 'def_id' => $def->def_id,
	// 'title' => $def->title,
	// 'data_type' => $def->data_type,
	// 'items' => $items
	// );
	// }
	// }
	// });

	// /**
	// * 集計処理
	// */
	// Log::info('create elms start.');
	// $elms = [];
	// foreach ($rows as $row) {
	// collect($row)->each(function ($elm) use (&$elms) {
	// array_push($elms, $elm);
	// });
	// }
	// Log::info('create elms finish.');

	// $groupList = [];

	// /**
	// * 回答ごとのループ処理検証(回答者×質問数の1ループ)
	// * やばい遅いww 111,088 ループで1分近くかかる。これはボツ。
	// */
	// Log::info('rows loop start.count:[' . count($elms) . ']');
	// foreach ($elms as $elm) {
	// /**
	// * 定義情報の取得
	// */
	// $tmp = collect($summary)->where('def_id', $elm['id']);
	// if ($tmp->count() == 0) {
	// continue;
	// } else {
	// $def = $tmp[$elm['id']];
	// }

	// // /**
	// // * data_type により処理わけを行う
	// // */
	// // switch ($def['data_type']) {
	// // case 'CHECKBOX':
	// // break;
	// // case 'GRID' || 'CHECKBOX_GRID':
	// // break;
	// // default:
	// // break;
	// // }

	// // }
	// // Log::info('rows loop finish.');
	// // return null;

	// Log::info('create def_id grouping start.');
	// $defGrps = collect($elms)->groupBy('id');
	// Log::info('create def_id grouping finish.');

	// Log::info('create group list start.');
	// foreach ($summary as $sum) {

	// /**
	// * 対象の回答のみ取得する
	// */
	// if (isset($defGrps[$sum['def_id']]) == false) {
	// continue;
	// }
	// $targetElms = $defGrps[$sum['def_id']];

	// $title = null;
	// $labels = [];
	// $datas = [];

	// /**
	// * data_type ごとに固有の集計処理を行う
	// */
	// switch ($sum['data_type']) {
	// case 'CHECKBOX':
	// collect(array_keys($sum['items']))->each(function ($item) use (&$sum, &$targetElms, &$title, &$labels, &$datas) {
	// $data = count(collect($targetElms)->filter(function ($val) use ($item) {
	// return in_array($item, $val['value'], true);
	// }));
	// $title = $sum['title'];
	// array_push($labels, $item);
	// array_push($datas, $data);
	// });
	// break;

	// case 'GRID':
	// for ($i = 0; $i < count($sum['rows']); $i ++) {
	// $targetAnswers = [];
	// foreach ($targetElms as $targetElm) {
	// array_push($targetAnswers, $targetElm['value'][$i]);
	// }

	// $title = $sum['rows'][$i]['title'];

	// foreach (array_keys($sum['rows'][$i]['items']) as $item) {
	// $data = count(collect($targetAnswers)->filter(function ($answer) use (&$item) {
	// return $answer == $item;
	// }));
	// array_push($labels, $item);
	// array_push($datas, $data);
	// }

	// if (empty($labels) == false && empty($datas) == false) {
	// $groupData['title'] = $title;
	// $groupData['labels'] = $labels;
	// $groupData['datas'] = $datas;
	// array_push($groupList, $groupData);
	// }

	// $title = null;
	// $labels = [];
	// $datas = [];
	// }
	// break;

	// case 'CHECKBOX_GRID':
	// for ($i = 0; $i < count($sum['rows']); $i ++) {
	// $targetAnswers = [];
	// foreach ($targetElms as $targetElm) {
	// array_push($targetAnswers, $targetElm['value'][$i]);
	// }

	// $title = $sum['rows'][$i]['title'];

	// foreach (array_keys($sum['rows'][$i]['items']) as $item) {
	// $data = count(collect($targetAnswers)->filter(function ($answer) use (&$item) {
	// return empty($answer) ? false : in_array($item, $answer);
	// }));
	// array_push($labels, $item);
	// array_push($datas, $data);
	// }

	// if (empty($labels) == false && empty($datas) == false) {
	// $groupData['title'] = $title;
	// $groupData['labels'] = $labels;
	// $groupData['datas'] = $datas;
	// array_push($groupList, $groupData);
	// }

	// $title = null;
	// $labels = [];
	// $datas = [];
	// }
	// break;

	// default:
	// collect(array_keys($sum['items']))->each(function ($item) use (&$sum, &$targetElms, &$title, &$labels, &$datas) {
	// $data = count(collect($targetElms)->filter(function ($val) use ($item) {
	// return $item == $val['value'];
	// }));
	// $title = $sum['title'];
	// array_push($labels, $item);
	// array_push($datas, $data);
	// });
	// }

	// if (empty($labels) == false && empty($datas) == false) {
	// $groupData['title'] = $title;
	// $groupData['labels'] = $labels;
	// $groupData['datas'] = $datas;
	// array_push($groupList, $groupData);
	// }
	// }
	// Log::info('create group list finish.');

	// return $groupList;
	// }
}
