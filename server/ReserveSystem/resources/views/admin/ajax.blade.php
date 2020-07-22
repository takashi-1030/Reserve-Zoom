@for($i = 9;$i < 17;$i++)
    @if($rec[$i.':00'] == NULL)
        <option value="{{ $i }}:00">{{ $i }}:00～</option>
    @endif
    @if($rec[$i.':30'] == NULL)
        <option value="{{ $i }}:30">{{ $i }}:30～</option>
    @endif
@endfor
@if($rec['17:00'] == NULL)
    <option value="17:00">17:00～</option>
@endif

