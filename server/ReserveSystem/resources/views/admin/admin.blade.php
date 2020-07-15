@extends('layout/admin_layout')
@section('library')
<link href="{{ asset('calendar/packages/core/main.css') }}" rel='stylesheet' />
<link href="{{ asset('calendar/packages/daygrid/main.css') }}" rel='stylesheet' />
<link href="{{ asset('calendar/packages/timegrid/main.css') }}" rel='stylesheet' />
<script src="{{ asset('calendar/packages/core/main.js') }}"></script>
<script src="{{ asset('calendar/packages/interaction/main.js') }}"></script>
<script src="{{ asset('calendar/packages/daygrid/main.js') }}"></script>
<script src="{{ asset('calendar/packages/timegrid/main.js') }}"></script>
<script src="{{ asset('calendar/packages/core/locales-all.js') }}"></script>
@stop

@section('styles')
<link href="{{ asset('css/event_modal.css') }}" rel="stylesheet">
@stop

@section('content')
<label>本日の予約</label>
@if(count($list) >= 1)
  <table class="table">
  @foreach($list as $item)
  <tr>
  <td>{{ $item['name'] }}様</td>
  <td>{{ date('G:i',strtotime($item['start'])) }}～</td>
  <td><a class="btn btn-primary" role="button" href="{{ $item['meeting_url'] }}">ミーティングを開始</a></td>
  </tr>
  @endforeach
  </table>
@else
  <p>本日の予約はありません</p>
@endif

<label>予約カレンダー</label>
<div id='calendar'></div>

<div class="modal event-modal">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content">
        <div class="modal_head"><label>予約内容</label></div>
        <table class="table">
        <tr><td>予約日</td><td class="event_date"></td></tr>
        <tr><td>時間</td><td class="event_time"></td></tr>
        <tr><td>お名前</td><td class="event_title"></td></tr>
        </table>
        <label class="link"></label>
        <label class="delete_link"></label>
        <button class="btn btn-secondary js-modal-close">戻る</button>
    </div>
</div>
@stop

@section('script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
      selectable: true,
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      navLinks: true,
      fixedWeekCount: false,
      locale: 'ja',
      defaultDate: new Date(),
      editable: true,
      events: "/setEvent",
      eventClick: function(info) {
        var year = info.event.start.getFullYear();
        var month = info.event.start.getMonth() + 1;
        var day = info.event.start.getDate();
        switch(info.event.start.getDay()) {
          case 0:
            var week = '日';
            break;
          case 1:
            var week = '月';
            break;
          case 2:
            var week = '火';
            break;
          case 3:
            var week = '水';
            break;
          case 4:
            var week = '木';
            break;
          case 5:
            var week = '金';
            break;
          case 6:
            var week = '土';
            break;
        }
        var hour = info.event.start.getHours();
        var minute = info.event.start.getMinutes();
        if(String(minute).length == 1){
          var minute = '0' + String(minute);
        }
        var id = info.event.id;
        var meeting_url = info.event.groupId;
        $('.event_date').text(year + '年' + month + '月' + day + '日(' + week + ')');
        $('.event_time').text(hour + ':' + minute + '～');
        $('.event_title').text(info.event.title);
        $('.link').html('<a class="btn btn-primary" role="button" href="' + meeting_url + '">ミーティングを開く</a>')
        $('.delete_link').html('<a class="btn btn-danger" role="button" href="admin/delete/' + id + '">予約を取り消す</a>')
        $('.event-modal').fadeIn();
      }
    });

    calendar.render();
  });

  $(function(){
    $('.js-modal-close').on('click',function(){
        $('.event-modal,.date-modal').fadeOut();
        return false;
    });
  });

</script>
@stop