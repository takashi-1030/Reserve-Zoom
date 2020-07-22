@extends('layout/layout')

@php
  $date = date('Y-m-d',$date_time);
  $start = date('G:i',$date_time);
  $date_str = date('Y年n月j日',strtotime($date));
  $week = date('w',strtotime($date));
  $week_str = ['日','月','火','水','木','金','土'];
  $after = strtotime('+ 30 minute',strtotime($start));
  $before = strtotime('- 30 minute',strtotime($start));
  $end = date('G:i',$after);
  $margin = date('G:i',$before);
@endphp

@section('content')
<h2 class="col">お客様情報入力</h2>
<div class="panel panel-default">
    <div class="panel-heading"><label>選択した日時 : {{ $date_str }}({{ $week_str[$week] }}) {{ $start }}～</label></div>
    <div class="panel-body">
        @if($errors->any())
        <div class="alert alert-danger">
        <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
        </div>
        @endif
        <form action="/input/check" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <label>法人名</label>
            <input type="text" name="corporate" class="form-control" value="{{ old('corporate') }}">
        </div>
        <div class="form-group">
            <label>お名前（必須）</label>
            <input type="text" name="name" class="form-control @if(!empty($errors->first('name'))) border-danger @endif" value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label>電話番号（必須）</label>
            <input type="text" name="tel" class="form-control @if(!empty($errors->first('tel'))) border-danger @endif" value="{{ old('tel') }}">
        </div>
        <div class="form-group">
            <label>メールアドレス（必須）</label>
            <input type="text" name="email" class="form-control @if(!empty($errors->first('email'))) border-danger @endif" value="{{ old('email') }}">
        </div>
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="start" value="{{ $start }}">
        <input type="hidden" name="end" value="{{ $end }}">
        <input type="hidden" name="margin" value="{{ $margin }}">
        <input type="submit" value="確認" class="btn btn-primary">
        <input type="button" value="戻る" onclick=history.back() class="btn btn-secondary">
        </form>
    </div>
</div>
@stop