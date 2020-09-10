<?php
namespace App\Http\Controllers;

use App\Model\Sheet;
use App\Model\SheetDef;
use App\Model\SheetDefAttr;
use App\Service\SheetDataService;
use App\Service\SheetDefinitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DateTime;
use Exception;

/**
 * アンケート定義コントローラ
 *
 * @author noshio
 *
 */
class SheetController extends Controller
{

	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * アンケート定義一覧ページへ遷移する
	 */
	public function sheets()
	{
		$sheets = Sheet::all()->where('enabled', 1);
		return view('sheet.list')->with([
			'sheets' => $sheets
		]);
	}

	/**
	 * 回答一覧ページへ遷移する
	 *
	 * @param Request $request
	 */
	public function answers(Request $request)
	{
		/**
		 * パラメータチェック
		 */
		$sheet_id = $request->input('sheet_id');
		if (empty($sheet_id)) {
			return view('home')->with('error', 'sheet_idが未指定のためアンケート内容取得処理を中断しました。');
		}

		$sheet_datas = DB::table('sheet_datas')->orderBy('data_id', 'desc')->get();

		$service = new SheetDataService($sheet_id);
		$titles = $service->getTitleArray();
		$records = $service->getAnswerList($sheet_datas);

		return view('sheet.answerlist')->with([
			'titles' => $titles,
			'records' => $records
		]);
	}

	/**
	 * アンケート内容をGoogleFromより取得してデータベースに反映する
	 *
	 * @param Request $request
	 */
	public function definition(Request $request)
	{
		/**
		 * パラメータチェック
		 */
		$sheet_id = $request->input('sheet_id');
		if (empty($sheet_id)) {
			return view('home')->with('error', 'sheet_idが未指定のためアンケート内容取得処理を中断しました。');
		}

		/**
		 * アンケート定義情報取得
		 */
		$sheet = DB::table('sheets')->where('sheet_id', $sheet_id)->first();
		if (empty($sheet)) {
			return view('home')->with('error', 'sheet情報が取得できませんでした。sheet_idを確認してください。');
		}

		/**
		 * アンケート内容をGoogleFormより取得する
		 */
		$key = $sheet->access_key;
		$url = $sheet->def_url . '?proc=1&formId=' . $sheet->form_id;
		$response = Http::withHeaders([
			'Authorization' => $key
		])->get($url);
		$obj = json_decode($response);
		if ($obj->status != '200') {
			return view('home')->with('info', 'アンケート内容の取得に失敗しました。[' . $obj->message . ']');
		}

		/**
		 * アンケート内容をデータベースより取得する
		 */
		$defs = DB::table('sheet_defs')->where('sheet_id', $sheet_id)->get();
		$attrs = DB::table('sheet_def_attrs')->where('sheet_id', $sheet_id)->get();

		/**
		 * アンケート内容のデータベース反映処理（更新処理を追加すること）
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
	 * 回答データ取得->データベース反映（sheet_datas テーブル）
	 *
	 * @param Request $request
	 */
	public function data(Request $request)
	{
		/**
		 * パラメータチェック
		 */
		$sheet_id = $request->input('sheet_id');
		if (empty($sheet_id)) {
			return view('home')->with('error', 'sheet_idが未指定のためアンケート回答取得処理を中断しました。');
		}

		// アンケート回答取得
		$sheet = DB::table('sheets')->where('sheet_id', $sheet_id)->first();

		$lastTime = null;
		$maxTime = DB::table('sheet_datas')->where('sheet_id', $sheet_id)
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
}