<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zoom;

class EventController extends Controller
{
    public function setEvent(Request $request)
    {
        $start = $this->formatDate($request->all()['start']);
        $end = $this->formatDate($request->all()['end']);

        $events = Zoom::select('id','corporate','name','date','start','meeting_url')->whereBetween('date',[$start,$end])->get();

        $newArr = [];
        foreach($events as $item){
            if($item['corporate'] == null){
                $newItem["title"] = $item["name"].'様';
            } else {
                $newItem["title"] = $item['corporate'].' '.$item["name"].'様';
            }
            $newItem["start"] = $item["date"].'T'.$item["start"];
            $newItem["end"] = $item["date"].'T'.$item["time"].'+05:00';
            $newItem["groupId"] = $item["meeting_url"];
            $newItem["id"] = $item["id"];
            $newItem["textColor"] = 'white';
            $newArr[] = $newItem;
        }

        echo json_encode($newArr);
    }

    public function formatDate($date)
    {
        return str_replace('T00:00:00+09:00','',$date);
    }
}
