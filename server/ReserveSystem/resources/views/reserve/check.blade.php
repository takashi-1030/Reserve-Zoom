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
                <td>{{ $input['time'] }}～</td>
                </tr>
                <tr>
                <td>利用時間</td>
                <td>{{ $input['duration'] }}分</td>
                </tr>
                <tr>
                <td>議題</td>
                <td>@if(isset($input['topic'])) {{ $input['topic'] }} @endif</td>
                </tr>
            </table>
        </div>
        <input type="hidden" name="date" value="{{ $input['date'] }}">
        <input type="hidden" name="time" value="{{ $input['time'] }}">
        <input type="hidden" name="duration" value="{{ $input['duration'] }}">
        <input type="hidden" name="topic" value="@if(isset($input['topic'])) {{ $input['topic'] }} @endif">
        <input type="submit" value="予約" class="btn btn-primary">
        <input type="button" value="戻る" onclick=history.back() class="btn btn-secondary">
        </form>
    </div>
</div>
@stop