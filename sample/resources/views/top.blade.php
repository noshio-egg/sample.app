<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--*/ Title /*-->
<title>top</title>
<!--/* style sheet */-->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/sample.css') }}" rel="stylesheet">
<!--/* javascript */-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	crossorigin="anonymous"></script>
<script src="{{ asset('js/sample.js') }}"></script>
</head>
<body>
	<div class="container">

		<!--*/ 編集エリア /*-->
		<div class="row">
			<div id="insert-area" class="col-sm-6 edit-area">
				<div class="col-sm-12">
					<h3>新規登録</h3>
				</div>
				<form name="insert-form" class="col-sm-12" action="./insert"
					method="post">
					@csrf
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">氏名</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" name="name"
								placeholder="氏名">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">性別</label>
						<div class="col-sm-10">
							<select class="form-control" id="gender" name="gender">
								<option>男性</option>
								<option>女性</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">生年月日</label>
						<div class="col-sm-10">
							<input type="date" class="form-control" id="birthday"
								name="birthday">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">備考</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="remarks"
								name="remarks" placeholder="備考">
						</div>
					</div>
					<button class="btn btn-primary btn-lg" type="submit">登録</button>
				</form>
			</div>

			<div id="update-area" class="col-sm-6 edit-area invisible">
				<div class="col-sm-12">
					<h3>更新</h3>
				</div>
				<form name="update_form" class="col-sm-12" action="./update"
					method="post">
					@csrf
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">氏名</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" name="name"
								placeholder="氏名">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">性別</label>
						<div class="col-sm-10">
							<select class="form-control" id="gender" name="gender">
								<option>男性</option>
								<option>女性</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">生年月日</label>
						<div class="col-sm-10">
							<input type="date" class="form-control" id="birthday"
								name="birthday">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">備考</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="remarks"
								name="remarks" placeholder="備考">
						</div>
					</div>
					<input type="hidden" name="id">
					<input type="hidden" name="page" value="<?php echo $page; ?>">
					<button class="btn btn-primary btn-lg" type="submit">更新</button>
					<button id="delete-row" class="btn btn-danger btn-lg" type="button">削除</button>
				</form>
			</div>
		</div>

		<!--*/ メッセージエリア /*-->
		<div class="row">
			<?php
			if (is_null($info) == false && empty($info->type) == false && empty($info->message) == false) {
				?>
				<div id="message-area" class="col-md-12 alert"
				data-type="<?php echo $info->type ?>">{{ $info->message }}</div>
			<?php } ?>
		</div>

		<!--*/ 一覧表示エリア /*-->
		<div class="row">
			<div class="col-md-12">
				<table id="user-list" class="table">
					<thead>
						<tr>
							<th>id</th>
							<th>name</th>
							<th>gender</th>
							<th>birthday</th>
							<th>remarks</th>
							<th>created_at</th>
							<th>updated_at</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$rows = '';
					foreach ($users as $user) {
						$rows .= '<tr id="user-row" data-name="' . $user->name . '" data-gender="' . $user->gender . '" data-birthday="' . $user->birthday . '" data-remarks="' . $user->remarks . '" data-id="' . $user->id . '">';
						$rows .= '<td>' . $user->id . '</td>';
						$rows .= '<td>' . $user->name . '</td>';
						$rows .= '<td>' . $user->gender . '</td>';
						$rows .= '<td>' . $user->birthday . '</td>';
						$rows .= '<td>' . $user->remarks . '</td>';
						$rows .= '<td>' . $user->created_at . '</td>';
						$rows .= '<td>' . $user->updated_at . '</td>';
						$rows .= '</tr>';
					}
					echo $rows;
					?>
					</tbody>
				</table>
			</div>
			<div class="col-md-12 page-links">
				<?php echo $users->links(); ?>
			</div>
		</div>
	</div>
	<form type="hidden" id="delete_form" name="delete_form"
		action="./delete" method="post">
		@csrf <input type="hidden" name="id">
	</form>
</body>
</html>