@extends('layout/layout')

@section('content')
<h2>予約確定</h2>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="form-group">
            <h2>ミーティングが作成されました</h2>
            <p>以下のリンクからミーティングを開始してください。</p>
            <a class="btn btn-primary" role="button" href="{{ $meeting['start_url'] }}">ミーティングを開始する</a><br>
            <a class="btn btn-primary" role="button" href="/">TOPに戻る</a>
        </div>
    </div>
</div>
@stop