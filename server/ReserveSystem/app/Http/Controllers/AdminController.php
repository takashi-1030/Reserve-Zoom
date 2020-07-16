<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zoom;
use App\Models\Time;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Mail;
use App\Mail\EditNotification;

class AdminController extends Controller
{
    public $api_base_url = 'https://api.zoom.us/v2/';

    public function getIndex()
    {
        $now = date('Y-m-d');
        $record = Zoom::where('date',$now)->get();

        return view('admin/admin')->with('list',$record);
    }

    public function edit($id)
    {
        $record = Zoom::find($id);

        return view('admin/edit/edit')->with('record',$record);
    }

    public function editCheck(Request $request,$id)
    {
        $input = $request->all() + ['id' => $id];

        return view('admin/edit/check')->with('input',$input);
    }

    public function editDone(Request $request,$id)
    {
        $request->session()->regenerateToken();
        
        $meeting = new MeetingController;
        $record = Zoom::find($id);
        $date = $request->old_date;
        $time = $request->old_start;
        $join_url = $record->join_url;
        $meeting_id = $record->meeting_id;
        $edit_meeting = $this->edit_meeting($request,$meeting_id);
        $edit_form = $this->edit_form($request,$edit_meeting,$record);
        $delete_time = $this->delete_time($date,$time);
        $create_time = $meeting->create_time($request);
        //$mail = $this->send_mail($request,$join_url);

        return redirect()->action('AdminController@getIndex');
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

    public function ajax(Request $request)
    {
        $date = $request->date;

        $result = Time::where('date',$date)->first();
        $view = view('admin/ajax')->with([
                                'rec' => $result,
                                'date' => $date
                              ]);
        $view = $view->render();

        return $view;
    }

    public function edit_form($request,$edit_meeting,$record)
    {
        $record->date = $request->date;
        $record->start = $request->start;
        $record->save();
    }

    public function edit_meeting(Request $request,$meeting_id)
    {
        $meeting = new MeetingController;
        $date = $request->date;
        $time = $request->start;
        $duration = 60;
        $method = 'PATCH';
        $url = '/v2/meetings/'.$meeting_id;
        $token = $meeting->create_access_token();

        $params = [
            'topic' => $request->name.'様',
            'type' => 2,
            'start_time' => $date.'T'.$time.':00',
            'duration' => $duration,
            'time_zone' => 'Asia/Tokyo',
            'settings' => [
                'use_pmi' => false
            ]
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

    public function send_mail(Request $request,$join_url)
    {
        $name = $request->name;
        $date_str = date('Y年n月j日',strtotime($request->date));
        $week = date('w',strtotime($request->date));
        $week_str = ['日','月','火','水','木','金','土'];
        $date = $date_str.'('.$week_str[$week].')';
        $start = $request->start;
        $to = $request->email;
        Mail::to($to)->send(new EditNotification($name,$date,$start,$join_url));
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
