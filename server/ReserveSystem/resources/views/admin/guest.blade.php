@extends('layout/admin_layout')

@section('content')
<h2>お客様情報一覧</h2>
<div class="panel panel-default">
    <div class="panel-body">
        <table class="table">
            <tr><th>法人名</th><th>お名前</th><th>電話番号</th><th>メールアドレス</th></tr>
            @foreach($list as $guest)
            <tr>
                <td>{{ $guest['corporate'] }}</td>
                <td>{{ $guest['name'] }}</td>
                <td>{{ $guest['tel'] }}</td>
                <td>{{ $guest['email'] }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@stop