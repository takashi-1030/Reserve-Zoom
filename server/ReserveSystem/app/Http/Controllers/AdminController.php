<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zoom;
use App\Models\Time;
use App\Http\Controllers\MeetingController;

class AdminController extends Controller
{
    public $api_base_url = 'https://api.zoom.us/v2/';

    public function getIndex()
    {
        $now = date('Y-m-d');
        $record = Zoom::where('date',$now)->get();

        return view('admin/admin')->with('list',$record);
    }

    public function delete($id)
    {
        $record = Zoom::find($id);

        return view('admin/delete')->with('record',$record);
    }

    public function deleteDone($id)
    {
        $record = Zoom::find($id);
        $meeting_id = $record->meeting_id;
        $date = $record->date;
        $time = $record->start;
        $delete_meeting = $this->delete_meeting($meeting_id);
        $delete_time = $this->delete_time($date,$time);
        $record->delete();

        return redirect()->action('AdminController@getIndex');
    }

    public function guestInfo()
    {
        $guest = Zoom::select('name','tel','email')->distinct()->get();

        return view('admin/guest')->with('list',$guest);
    }

    public function delete_meeting($meeting_id)
    {
        $meeting = new MeetingController;
        $method = 'DELETE';
        $url = 'meetings/'.$meeting_id;
        $token = $meeting->create_access_token();

        $params = [
            'occurrence_id' => 'Bearer '.$token,
        ];
        $client_params = [
            'base_uri' => $this->api_base_url,
            'json' => $params
        ];
        $options = [
            'headers' => [
                'Accept' => 'application/json, application/xml',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ]
        ];
        $res = $meeting->call_api($url,$client_params,$options,$method);

        return $res;
    }

    public function delete_time($date,$time)
    {
        $start = date('G:i',strtotime($time));
        $end = date('G:i',strtotime('+ 30 minute',strtotime($time)));
        $margin = date('G:i',strtotime('- 30 minute',strtotime($time)));
        $before = date('G:i',strtotime('- 60 minute',strtotime($time)));
        $time_record = Time::where('date',$date)->first();

        if($before == '8:00'){
            $time_record->$start = NULL;
            $time_record->$end = NULL;
            $time_record->save();
        } elseif($before == '8:30'){
            $time_record->$margin = NULL;
            $time_record->$start = NULL;
            $time_record->$end = NULL;
            $time_record->save();
        } else {
            if($time_record->$before == NULL){
                $time_record->$margin = NULL;
            }
            $time_record->$start = NULL;
            $time_record->$end = NULL;
            $time_record->save();
        }
    }

    //Reserve System
    public function getReserve($id)
    {
        $record = Reserve::find($id);

        return view('admin/reserve')->with(
            'input', [
                'id' => $id,
                'name' => $record->name,
                'tel' => $record->tel,
                'email' => $record->email,
                'date' => $record->date,
                'time' => $record->time,
                'number' => $record->number,
                'seat' => $record->seat,
                'ok_flg' => $record->ok_flg,
            ]);
    }

    public function reserveEdit($id)
    {
        $record = Reserve::find($id);

        return view('admin/edit/edit')->with(
            'input', [
                'id' => $id,
                'name' => $record->name,
                'tel' => $record->tel,
                'email' => $record->email,
                'date' => $record->date,
                'time' => $record->time,
                'number' => $record->number,
                'seat' => $record->seat,
            ]);
    }

    public function editCheck(Request $request,$id)
    {
        $input = $request->all() + ['id' => $id];
        return view('admin/edit/check')->with('input',$input);
    }

    public function editDone(Request $request,$id)
    {
        $reserve_record = Reserve::find($id);
        $reserve_record->name = $request->name;
        $reserve_record->tel = $request->tel;
        $reserve_record->email = $request->email;
        $reserve_record->date = $request->date;
        $reserve_record->time = $request->time;
        $reserve_record->number = $request->number;
        $reserve_record->seat = $request->seat;
        $reserve_record->save();

        return redirect()->action('AdminController@getIndex');
    }

    public function addReserve(Request $request)
    {
        $date = $request->all();

        return view('admin/add/add')->with('date',$date);
    }

    public function reserveCheck(Request $request)
    {
        $input = $request->all();

        return view('admin/add/check')->with('input',$input);
    }
    public function addDone(Request $request)
    {
        $reserve_record = new Reserve;
        $reserve_record->name = $request->name;
        $reserve_record->tel = $request->tel;
        $reserve_record->email = $request->email;
        $reserve_record->date = $request->date;
        $reserve_record->time = $request->time;
        $reserve_record->number = $request->number;
        $reserve_record->seat = $request->seat;
        $reserve_record->save();

        return redirect()->action('AdminController@getIndex');
    }

    public function reserveConfirm($id)
    {
        $reserve_record = Reserve::find($id);
        $reserve_record->ok_flg = 'OK';
        $reserve_record->save();

        return redirect()->action('AdminController@getIndex');
    }
}
