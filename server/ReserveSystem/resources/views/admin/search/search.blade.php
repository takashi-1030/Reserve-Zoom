@extends('layout/admin_layout')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<link href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css" rel="stylesheet">
@stop

@section('content')
<h2>検索</h2>
<div class="panel panel-default">
    <div class="panel-body">
        <form action="/admin/search/done" method="post">
        {{ csrf_field() }}
        <label>予約日で検索</label>
        <div class="form-group">
            <input type="text" name="date" id="date" class="form-control date">
        </div>
        <label>お客様名で検索</label>
        <div class="form-group">
            <input type="text" name="name" class="form-control">
        </div>
        <input type="submit" value="検索" class="btn btn-primary">
        <input type="button" value="戻る" onclick=history.back() class="btn btn-secondary">
        </form>
    </div>
</div>
@stop

@section('script')
<script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ja.js"></script>
<script>
    flatpickr(document.getElementById('date'),{
        locale: 'ja',
        dateFormat: "Y-m-d",
    });
</script>
@stop