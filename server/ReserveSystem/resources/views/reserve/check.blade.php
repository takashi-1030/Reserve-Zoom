@extends('layout/layout')

@php
  $date = $input['date'];
  $date_str = date('Y年n月j日',strtotime($date));
  $week = date('w',strtotime($date));
  $week_str = ['日','月','火','水','木','金','土'];
@endphp

@section('content')
<h2>予約内容確認</h2>
<div class="panel panel-default">
    <div class="panel-body">
        <form action="/reserve_meeting" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <label>以下の内容でミーティングを作成します。</label>
            <table class="table">
                <tr>
                <td>日にち</td>
                <td>{{ $date_str }}({{ $week_str[$week] }})</td>
                </tr>
                <tr>
                <td>開始時刻</td>
                <td>{{ $input['start'] }}～</td>
                </tr>
                @if($input['corporate'] != '')
                    <tr>
                    <td>法人名</td>
                    <td>{{ $input['corporate'] }}</td>
                    </tr>
                    <tr>
                @endif
                <td>お名前</td>
                <td>{{ $input['name'] }}</td>
                </tr>
                <tr>
                <td>電話番号</td>
                <td>{{ $input['tel'] }}</td>
                </tr>
                <tr>
                <td>メールアドレス</td>
                <td>{{ $input['email'] }}</td>
                </tr>
            </table>
        </div>
        <input type="hidden" name="date" value="{{ $input['date'] }}">
        <input type="hidden" name="start" value="{{ $input['start'] }}">
        <input type="hidden" name="end" value="{{ $input['end'] }}">
        <input type="hidden" name="margin" value="{{ $input['margin'] }}">
        <input type="hidden" name="corporate" value="{{ $input['corporate'] }}">
        <input type="hidden" name="name" value="{{ $input['name'] }}">
        <input type="hidden" name="tel" value="{{ $input['tel'] }}">
        <input type="hidden" name="email" value="{{ $input['email'] }}">
        <input type="submit" value="予約" class="btn btn-primary">
        <input type="button" value="戻る" onclick=history.back() class="btn btn-secondary">
        </form>
    </div>
</div>
@stop