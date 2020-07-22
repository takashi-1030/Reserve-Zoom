@extends('layout/admin_layout')

@section('content')
<h2>検索結果</h2>
<div class="panel panel-default">
    <div class="panel-body">
        <table class="table">
            <tr><th>予約日</th><th>開始時刻</th><th>法人名</th><th>お名前</th><th>電話番号</th><th>メールアドレス</th></tr>
            @foreach($record as $item)
            <tr>
                <td>{{ $item['date'] }}</td>
                <td>{{ date('G:i',strtotime($item['start'])) }}～</td>
                <td>{{ $item['corporate'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['tel'] }}</td>
                <td>{{ $item['email'] }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@stop