<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Message;
use App\MessageType;

class SampleController extends Controller
{

	/**
	 * 基底画面(全レコード表示)
	 *
	 * @param Request $request
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function top(Request $request)
	{
		$page = '';
		if (isset($request->page)) {
			$page = '?page=' . $request->page;
		}
		$info = null;
		if (Session::has('info')) {
			$info = Session::get('info');
		}
		$users = DB::table('user')->paginate(10);
		return view("top", compact('users', 'info', 'page'));
	}

	public function search(Request $request)
	{}

	/**
	 * レコード登録処理
	 *
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function insert(Request $request)
	{
		$name = $request->input('name');
		$gender = $request->input('gender');
		$birthday = $request->input('birthday');
		$remarks = $request->input('remarks');

		if (empty($name) || empty($gender) || empty($birthday)) {
			return redirect('/top')->with('info', new Message(MessageType::EROR, '登録処理に失敗しました。必須情報が不足しています。'));
		}

		DB::table('user')->insert(array(
			'name' => $name,
			'gender' => $gender,
			'birthday' => $birthday,
			'remarks' => $remarks
		));
		return redirect('/top')->with('info', new Message(MessageType::INFO, '登録処理に成功しました。'));
	}

	/**
	 * レコード更新処理
	 *
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request)
	{
		$url = '/top';
		if (empty($request->input('page')) == false) {
			$url .= $request->input('page');
		}
		try {
			return DB::transaction(function () use ($request, $url) {
				$id = $request->input('id');
				$name = $request->input('name');
				$gender = $request->input('gender');
				$birthday = $request->input('birthday');
				$remarks = $request->input('remarks');

				if (empty($name) || empty($gender) || empty($birthday)) {
					return redirect('/top')->with('info', new Message(MessageType::EROR, '更新処理に失敗しました。必須情報が不足しています。'));
				}

				DB::table('user')->where('id', $id)
					->lockForUpdate()
					->get();
				DB::table('user')->where('id', $id)->update(array(
					'name' => $name,
					'gender' => $gender,
					'birthday' => $birthday,
					'remarks' => $remarks
				));
				return redirect($url)->with('info', new Message(MessageType::INFO, '更新処理に成功しました。'));
			});
		} catch (Exception $e) {
			return redirect($url)->with('info', new Message(MessageType::EROR, '更新処理に失敗しました。[' . $e->getMessage() . ']'));
		}
	}

	/**
	 * 削除処理
	 *
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function delete(Request $request)
	{
		try {
			return DB::transaction(function () use ($request) {
				$id = $request->input('id');
				if (empty($id)) {
					return redirect('/top')->with('info', new Message(MessageType::EROR, '削除処理に失敗しました。'));
				}

				DB::table('user')->where('id', $id)
					->lockForUpdate()
					->get();
				DB::table('user')->where('id', $id)->delete();
				return redirect('/top')->with('info', new Message(MessageType::INFO, '削除処理に成功しました。'));
			});
		} catch (Exception $e) {
			return redirect('/top')->with('info', new Message(MessageType::EROR, '削除処理に失敗しました。[' . $e->getMessage() . ']'));
		}
	}
}