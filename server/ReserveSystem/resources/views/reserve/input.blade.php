@extends('layout/layout')

@php
  $date = date('Y-m-d',$date_time);
  $start = date('G:i',$date_time);
  $date_str = date('Y年n月j日',strtotime($date));
  $week = date('w',strtotime($date));
  $week_str = ['日','月','火','水','木','金','土'];
  $time = strtotime('+ 30 minute',strtotime($start));
  $end = date('G:i',$time);
@endphp

@section('content')
<h2>お客様情報入力</h2>
<div class="panel panel-default">
    <div class="panel-heading"><label>選択した日時 : {{ $date_str }}({{ $week_str[$week] }}) {{ $start }}～</label></div>
    <div class="panel-body">
        <form action="/input/check" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <label>お名前（必須）</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label>電話番号（必須）</label>
            <input type="text" name="tel" class="form-control">
        </div>
        <div class="form-group">
            <label>メールアドレス（必須）</label>
            <input type="text" name="email" class="form-control">
        </div>
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="start" value="{{ $start }}">
        <input type="hidden" name="end" value="{{ $end }}">
        <input type="submit" value="確認" class="btn btn-primary">
        <input type="button" value="戻る" onclick=history.back() class="btn btn-secondary">
        </form>
    </div>
</div>
@stop