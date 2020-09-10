@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
    	<div class="col-lg-12">
    		<table class="table">
    			<thead>
    				<tr>
    				@foreach ($titles as $title)
    					<td>{{ $title['title'] }}</td>
    				@endforeach
    				</tr>
    			</thead>
    			<tbody>
    			@foreach ($records as $key => $record)
    				<tr>
    				@foreach ($titles as $title)
    					<td>{{ $record[$title['id']] }}</td>
    				@endforeach
    				</tr>
    			@endforeach
    			</tbody>
    		</table>
    	</div>
    </div>
</div>
@endsection