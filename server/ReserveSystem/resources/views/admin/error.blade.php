@extends('layout/layout')

@section('content')
<h2>エラー</h2>
<div class="panel panel-default">
    <div class="panel-body">
        <h1>問題が発生しました</h1>
        <p>問題が発生しました。<br>もう一度トップページからやり直すか、サーバーの状態を確認してください。</p><br>
        <a class="btn btn-primary" role="button" href="/admin">予約一覧に戻る</a>
    </div>
</div>
@stop