@extends('layout/admin_layout')

@php
  $date = $record['date'];
  $date_str = date('Y年n月j日',strtotime($date));
  $week = date('w',strtotime($date));
  $week_str = ['日','月','火','水','木','金','土'];
@endphp

@section('content')
<h2>予約取り消し</h2>
<div class="panel panel-default">
    <div class="panel-body">
        <p>以下の予約を取り消します</p>
        <label>日時</label>
        <table class="table">
            <tr><th>日にち</th><td>{{ $date_str }}({{ $week_str[$week] }})</td></tr>
            <tr><th>開始時刻</th><td>{{ date('G:i',strtotime($record['start'])) }}～</td></tr>
        </table>
        <label>お客様情報</label>
        <table class="table">
            @if($record['corporate'] != null)
                <tr><th>法人名</th><td>{{ $record['corporate'] }}</td></tr>
            @endif
            <tr><th>お名前</th><td>{{ $record['name'] }}様</td></tr>
            <tr><th>電話番号</th><td>{{ $record['tel'] }}</td></tr>
            <tr><th>メールアドレス</th><td>{{ $record['email'] }}</td></tr>
        </table>
        <a class="btn btn-danger" role="button" href="/admin/delete/done/{{ $record['id'] }}">予約取り消し</a>
        <a class="btn btn-secondary" role="button" href="/admin">戻る</a>
    </div>
</div>
@stop