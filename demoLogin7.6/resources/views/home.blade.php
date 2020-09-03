@extends('layouts.app') @section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">{{ __('Dashboard') }}</div>

				<div class="card-body">
					@if (session('status'))
					<div class="alert alert-success" role="alert">{{ session('status')
						}}</div>
					@endif {{ __('You are logged in!') }}
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<table class="table">
				<thead>
				<tr>
					<td>row_id</td>
					<td>inputed_at</td>
					<td>sheet_id</td>
					<td>data</td>
					<td>created_at</td>
					<td>updated_at</td>
					</tr>
				</thead>
				<tbody>
					@foreach ($records as $record)
					<tr>
						<td>{{$record->row_id}}</td>
						<td>{{$record->inputed_at}}</td>
						<td>{{$record->sheet_id}}</td>
						<td>{{$record->data}}</td>
						<td>{{$record->created_at}}</td>
						<td>{{$record->updated_at}}</td>
					</tr>
					@endforeach

				</tbody>
			</table>

			{{ $records->links() }}
		</div>
	</div>
</div>
@endsection
