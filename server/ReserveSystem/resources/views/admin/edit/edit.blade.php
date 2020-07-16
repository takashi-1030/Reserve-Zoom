@extends('layout/admin_layout')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<link href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css" rel="stylesheet">
@stop

@section('content')
<h2>予約内容変更</h2>
<div class="panel panel-default">
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
        <form action="/admin/edit/{{ $record['id'] }}" method="post">
        {{ csrf_field() }}
        <label>予約日</label>
        <div class="form-inline">
            <div class="form-group mb-2">
                <input type="text" name="date" id="date" value="{{ $record['date'] }}" class="form-control date" style="width: 200px">
                <span class="time_check btn btn-primary" role="button">予約可能な時間を確認</span>
            </div>
        </div><br>
        <label>開始時刻</label>
        <div class="form-group">
        <select class="form-control" name="start" style="width: 200px">
        </select>
        </div>
        <input type="hidden" name="old_date" value="{{ $record['date'] }}">
        <input type="hidden" name="old_start" value="{{ $record['start'] }}">
        <input type="hidden" name="name" value="{{ $record['name'] }}">
        <input type="hidden" name="tel" value="{{ $record['tel'] }}">
        <input type="hidden" name="email" value="{{ $record['email'] }}">
        <input type="submit" value="確認" class="btn btn-primary">
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
        minDate: new Date()
    });

    $(function(){
        $('.time_check').on('click',function(){
            var str = $('.date').val();
            var data = {'date': str};
            $.ajax({
            type: 'get',
            data: data,
            datatype: 'html',
            url: '/meeting/edit'
            })
            .done(function(view){
            $('select').html(view);
            })
            .fail(function(view){
            alert('error');
            });
        });
    });
</script>
@stop