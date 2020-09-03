<?php
namespace App\Http\Controllers;

use App\SheetDef;
use App\SheetDefAttr;
use App\Service\SheetDefinitionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DateTime;
use Exception;

class HomeController extends Controller
{

	const LIST_TYPES = [
		'SCALE',
		'GRID',
		'MULTIPLE_CHOICE',
		'CHECKBOX_GRID',
		'CHECKBOX',
		'LIST'
	];

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$access = DB::table('research')->where('id', 1)->first();

		$key = $access->access_key;
		$url = $access->def_url;
		$response = Http::withHeaders([
			'Authorization' => $key
		])->get($url);
		$obj = json_decode($response);
		if ($obj->status != '200') {
			return view('home')->with('info', 'アンケートの取得に失敗しました。[' . $obj->message . ']');
		}

		/**
		 * 取得結果が存在しない場合には何もしない
		 */
		if (empty($obj) == false) {
			/**
			 * アンケート質問内容登録処理
			 */
			$keys = array_keys($obj->data[0]);
			$content = DB::table('research_content')->where('research_id', $access->id)->get();
			if (count($content) == 0) {
				$param = [];
				for ($i = 0; $i < count($keys); $i ++) {
					array_push($param, [
						'research_id' => $access->id,
						'column_index' => $i,
						'value' => $keys[$i]
					]);
				}
				try {
					DB::transaction(function () use ($param) {
						DB::table('research_content')->insert($param);
					});
				} catch (Exception $e) {
					return view('home')->with('info', '質問内容の初期登録処理に失敗しました。[' . $e->getMessage() . ']');
				}
			} else {
				if ((count($keys) == count($content)) == false) {
					// エラー処理
					// 登録された質問項目数と取得した質問項目数が一致しない
					return view('home');
				}
			}
			/**
			 * アンケート回答内容登録処理
			 */
			$answer_index = DB::table('research_answer')->where('research_id', $access->id)->max('answer_index') ?? - 1;
			if ($answer_index < count($obj) - 1) {
				$param = [];
				$items = array_values($obj);
				for ($i = 0; $i < count($items); $i ++) {
					if ($i <= $answer_index) {
						continue;
					}
					$values = array_values($items[$i]);
					for ($n = 0; $n < count($values); $n ++) {
						$spltval = explode(',', $values[$n]);
						if (count($spltval) > 1) {
							foreach ($spltval as $val) {
								array_push($param, [
									'research_id' => $access->id,
									'answer_index' => $i,
									'column_index' => $n,
									'value' => trim($val)
								]);
							}
						} else {
							array_push($param, [
								'research_id' => $access->id,
								'answer_index' => $i,
								'column_index' => $n,
								'value' => $values[$n]
							]);
						}
					}
				}
				try {
					DB::transaction(function () use ($param) {
						DB::table('research_answer')->insert($param);
					});
				} catch (Exception $e) {
					return view('home')->with('info', 'アンケート回答の登録処理に失敗しました。[' . $e->getMessage() . ']');
				}
			}
		}
		return view('home');
	}

	/**
	 * アンケート定義をGoogleFromより取得してデータベースに反映する
	 */
	public function definition()
	{
		/**
		 * アンケート定義取得をGoogleFormより取得する
		 */
		$sheet = DB::table('sheets')->where('sheet_id', 2)->first();
		$key = $sheet->access_key;
		$url = $sheet->def_url . '?proc=1&formId=' . $sheet->form_id;
		$response = Http::withHeaders([
			'Authorization' => $key
		])->get($url);
		$obj = json_decode($response);
		if ($obj->status != '200') {
			return view('home')->with('info', 'アンケートの取得に失敗しました。[' . $obj->message . ']');
		}

		/**
		 * アンケート定義情報をデータベースより取得する
		 */
		$defs = DB::table('sheet_defs')->where('sheet_id', 2)->get();
		$attrs = DB::table('sheet_def_attrs')->where('sheet_id', 2)->get();

		/**
		 * アンケート定義情報のデータベース反映処理（更新処理を追加すること）
		 */
		if (empty($obj) == false || empty($obj->data) == false) {

			// regist parameters.
			$insDefParams = [];
			$updDefParams = [];
			$insAttrParams = [];

			$records = json_decode($obj->data);
			foreach ($records as $record) {

				/**
				 * "sheet_defs" への登録/更新パラメータを生成する
				 */
				$exists = collect($defs)->contains('def_id', $record->id);
				if ($exists) {
					if (collect($defs)->contains(function ($def) use (&$record) {
						return $def->def_id == $record->id && $def->index == $record->index && $def->title == $record->title && $def->data_type == $record->type;
					}) == false) {
						array_push($updDefParams, [
							'def_id' => $record->id,
							'index' => $record->index,
							'title' => $record->title,
							'data_type' => $record->type
						]);
					}
				} else {
					array_push($insDefParams, [
						'def_id' => $record->id,
						'sheet_id' => $sheet->sheet_id,
						'index' => $record->index,
						'title' => $record->title,
						'data_type' => $record->type
					]);
				}

				/**
				 * "sheet_def_attrs"('LIST') への登録パラメータを生成する
				 */
				if (empty($record->list) == false) {
					collect($record->list)->each(function ($item) use (&$record, &$attrs, &$sheet, &$insAttrParams) {
						$exists = false;
						$exists = collect($attrs)->contains(function ($attr) use (&$record, &$item) {
							return $attr->def_id == $record->id && $attr->key == 'LIST' && $attr->value == $item;
						});
						if ($exists == false) {
							array_push($insAttrParams, [
								'sheet_id' => $sheet->sheet_id,
								'def_id' => $record->id,
								'key' => 'LIST',
								'value' => $item
							]);
						}
					});
				}

				/**
				 * "sheet_def_attrs"('ROW') への登録パラメータを生成する
				 */
				if (empty($record->rows) == false) {
					collect($record->rows)->each(function ($item) use (&$record, &$attrs, &$sheet, &$insAttrParams) {
						$exists = false;
						$exists = collect($attrs)->contains(function ($attr) use (&$record, &$item) {
							return $attr->def_id == $record->id && $attr->key == 'ROW' && $attr->value == $item;
						});
						if ($exists == false) {
							array_push($insAttrParams, [
								'sheet_id' => $sheet->sheet_id,
								'def_id' => $record->id,
								'key' => 'ROW',
								'value' => $item
							]);
						}
					});
				}
			}

			/**
			 * データベース反映(update or insert.)
			 */
			try {
				DB::transaction(function () use ($insDefParams, $updDefParams, $insAttrParams) {
					/**
					 * insert sheet_defs
					 */
					if (empty($insDefParams) == false) {
						SheetDef::insert($insDefParams);
					}

					/**
					 * update sheet_defs.
					 */
					if (empty($updDefParams) == false) {
						collect($updDefParams)->each(function ($item) {
							SheetDef::where('def_id', $item['def_id'])->update($item);
						});
					}

					/**
					 * insert into sheet_def_attrs
					 */
					if (empty($insAttrParams) == false) {
						SheetDefAttr::insert($insAttrParams);
					}
				});
			} catch (Exception $e) {
				return view('home')->with('info', 'アンケート定義の登録処理に失敗しました。[' . $e->getMessage() . ']');
			}
		}
	}

	/**
	 * 回答データ取得->データベース反映
	 */
	public function getAnswer()
	{
		// アンケート回答取得
		$research = DB::table('research')->where('id', 1)->first();
		$key = $research->access_key;
		$url = $research->def_url . '?proc=2&time=2020-07-16T16:54:36.012Z';
		$response = Http::withHeaders([
			'Authorization' => $key
		])->get($url);
		$obj = json_decode($response);
		if ($obj->status != '200') {
			return view('home')->with('info', 'アンケート回答の取得に失敗しました。[' . $obj->message . ']');
		}

		/**
		 * アンケート回答データのデータベース反映処理
		 */
		if (empty($obj) == false || empty($obj->data) == false) {
			$param = [];

			$records = json_decode($obj->data);
			foreach ($records as $record) {
				array_push($param, [
					'inputed_at' => DateTime::createFromFormat('Y-m-d\TH:i:s.v', $record->timestamp),
					'sheet_id' => $research->id,
					'data' => $record->data
				]);
			}

			try {
				DB::transaction(function () use ($param) {
					DB::table('sheet_rows')->insert($param);
				});
			} catch (Exception $e) {
				return view('home')->with('info', 'アンケート回答の登録処理に失敗しました。[' . $e->getMessage() . ']');
			}
		}
	}

	/**
	 * 回答データ取得->データベース反映（sheet_datas テーブル）
	 */
	public function getAnswer2()
	{
		// アンケート回答取得
		$sheet = DB::table('sheets')->where('sheet_id', 2)->first();

		$lastTime = null;
		$maxTime = DB::table('sheet_datas')->where('sheet_id', 2)
			->get('answer_at')
			->max('answer_at');
		if ($maxTime != null) {
			$lastTime = DateTime::createFromFormat('Y-m-d H:i:s.v', $maxTime)->format('Y-m-d\TH:i:s.v');
		} else {
			$lastTime = '2020-01-01T00:00:00.000';
		}

		$key = $sheet->access_key;
		$url = $sheet->def_url . '?proc=2&time=' . $lastTime . '&formId=' . $sheet->form_id;
		$response = Http::withHeaders([
			'Authorization' => $key
		])->get($url);
		$obj = json_decode($response);
		if ($obj->status != '200') {
			return view('home')->with('info', 'アンケート回答の取得に失敗しました。[' . $obj->message . ']');
		}

		/**
		 * アンケート回答データのデータベース反映処理
		 */
		if (empty($obj) == false || empty($obj->data) == false) {

			$sheet_id = 2;
			$service = new SheetDefinitionService($sheet_id);

			$records = json_decode($obj->data);
			foreach ($records as $record) {

				$param = [];
				$dtl_param = [];
				$data_id = null;

				array_push($param, [
					'sheet_id' => $sheet->sheet_id,
					'answer_at' => DateTime::createFromFormat('Y-m-d\TH:i:s.v', $record->timestamp)->format('Y-m-d H:i:s.v')
				]);

				try {
					DB::transaction(function () use (&$param, &$data_id) {
						DB::table('sheet_datas')->insert($param);
						$data_id = DB::getPdo()->lastInsertId();
					});
				} catch (Exception $e) {
					Log::error('アンケート回答の登録処理に失敗しました。[定義ID:' . $sheet->sheet_id . ']、[エラー詳細:' . $e->getTraceAsString() . ']');
					continue;
				}

				$datas = json_decode($record->data);
				foreach ($datas as $data) {
					$def = $service->getDef($data->id);
					$add_params = $service->generateInsParam($data, $data_id);
					collect($add_params)->each(function ($add_param) use (&$dtl_param) {
						array_push($dtl_param, $add_param);
					});
				}
				try {
					DB::transaction(function () use (&$param, &$dtl_param) {
						DB::table('sheet_data_details')->insert($dtl_param);
					});
				} catch (Exception $e) {
					Log::error('アンケート回答の登録処理に失敗しました。[定義ID:' . $def->def_id . ']、[エラー詳細:' . $e->getTraceAsString() . ']');
					continue;
				}
			}
		}
	}

	/**
	 * データ取得、一覧表示検証
	 */
	public function disp()
	{
		$records = DB::table('sheet_rows')->orderBy('row_id')->simplePaginate(100);
		return view('home')->with('records', $records);
	}

	/**
	 * 検索・分析検証
	 */
	public function search()
	{
		$datas = DB::table('research')->leftJoin('research_content', 'research.id', '=', 'research_content.research_id')
			->leftJoin('research_answer', function ($join) {
			$join->on('research_content.research_id', '=', 'research_answer.research_id');
			$join->on('research_content.column_index', '=', 'research_answer.column_index');
		})
			->where('research.id', 1)
			->select('research.id', 'research.name', 'research_content.column_index', 'research_content.value as research_content', 'research_answer.answer_index', 'research_answer.value as research_answer')
			->orderByRaw(' research_answer.answer_index, research_content.column_index')
			->get();
		// 変数でもイケるかチェック -> イケる
		$key_col = 'research_content';
		$val = 'フィードバックの種類';
		$result = $datas->whereIn('answer_index', $datas->where($key_col, $val)
			->where('research_answer', '体質改善')
			->pluck('answer_index'))
			->values();
		return view('search')->with('datas', $result);
	}
}
