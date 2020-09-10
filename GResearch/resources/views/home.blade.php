@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
<!--     <div class="row justify-content-center"> -->
<!--         <div class="col-md-8"> -->
<!--             <div class="card"> -->
<!--                 <div class="card-header">{{ __('Dashboard') }}</div> -->

<!--                 <div class="card-body"> -->
<!--                     @if (session('status')) -->
<!--                         <div class="alert alert-success" role="alert"> -->
<!--                             {{ session('status') }} -->
<!--                         </div> -->
<!--                     @endif -->

<!--                     {{ __('You are logged in!') }} -->
<!--                 </div> -->
<!--             </div> -->
<!--         </div> -->

		@can('developer')
        <div class="col-md-6">
            <a href="">
                <div class="main-menu btn btn-primary shadow"><h3>店舗管理</h3></div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="">
                <div class="main-menu btn btn-primary shadow"><h3>アカウント管理</h3></div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="">
                <div class="main-menu btn btn-primary shadow"><h3>アンケート管理</h3></div>
            </a>
        </div>
        @endcan
        <div class="col-md-6">
            <a href="sheet/list">
                <div class="main-menu btn btn-primary shadow"><h3>分析</h3></div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="sheet/list">
                <div class="main-menu btn btn-primary shadow"><h3>回答内容確認</h3></div>
            </a>
        </div>
    </div>
</div>
@endsection
