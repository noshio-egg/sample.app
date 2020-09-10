@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>参照可能なアンケート一覧</h3>
        </div>
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <th>アンケート名称</th>
                    <th>適用開始日時</th>
                    <th>適用終了日時</th>
                </thead>
                <tbody>
                    @foreach ($sheets as $sheet)
                    <tr>
                        <td><a class="stretched-link" href='answer/list?sheet_id={{ $sheet->sheet_id }}'>{{ $sheet->sheet_nm }}</a></td>
                        <td>{{ $sheet->start_time }}</td>
                        <td>{{ $sheet->end_time }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
