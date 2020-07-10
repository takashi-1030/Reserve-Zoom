@for($i = 9;$i < 17;$i++)
<tr>
<th>{{ $i }}:00</th>
<td>
@if($rec[$i.':00'] == NULL)
<a href="/input/{{ $date }} {{ $i }}:00:00">予約可</a>
@else
予約不可
@endif
</td>
</tr>
<tr>
<th>{{ $i }}:30</th>
<td>
@if($rec[$i.':30'] == NULL)
<a href="/input/{{ $date }} {{ $i }}:30:00">予約可</a>
@else
予約不可
@endif
</td>
</tr>
@endfor
<tr>
<th>17:00</th>
<td>
@if($rec['17:00'] == NULL)
<a href="/input/{{ $date }} 17:00:00">予約可</a>
@else
予約不可
@endif
</td>