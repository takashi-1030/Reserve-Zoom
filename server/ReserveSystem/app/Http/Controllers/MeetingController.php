<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Builder;
use Validator;
use App\Models\Zoom;
use App\Models\Time;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notification;

class MeetingController extends Controller
{
    public $api_base_url = 'https://api.zoom.us/v2/';

    public function reserve()
    {
        return view('reserve/reserve');
    }

    public function ajax(Request $request)
    {
        $date = $request->date;

        $result = Time::where('date',$date)->first();
        $view = view('ajax')->with([
                                'rec' => $result,
                                'date' => $date
                              ]);
        $view = $view->render();

        return $view;
    }

    public function input($date)
    {
        $date_str = strtotime($date);

        return view('reserve/input')->with('date_time',$date_str);
    }

    public function check(Request $request)
    {
        $input = $request->all();

        $rules = [
            'name' => 'required',
            'tel' => 'required|numeric',
            'email' => 'required|email'
        ];
        Validator::make($input,$rules)->validate();

        return view('reserve/check')->with('input',$input);
    }

    public function reserveMeeting(Request $request)
    {
        $meeting = $this->create_meeting($request);
        $form = $this->create_form($request,$meeting);
        $time = $this->create_time($request);
        //$mail = $this->send_mail($request,$meeting);

        $request->session()->regenerateToken();

        return view('reserve/reserve_meeting')->with('meeting',$meeting);
    }

    public function create_form(Request $request,$meeting)
    {
        $form = new Zoom;
        $form->name = $request->name;
        $form->tel = $request->tel;
        $form->email = $request->email;
        $form->date = $request->date;
        $form->start = $request->start;
        $form->meeting_url = $meeting['start_url'];
        $form->join_url = $meeting['join_url'];
        $form->meeting_id = $meeting['id'];
        $form->save();
    }

    public function create_time(Request $request)
    {
        $date = $request->date;
        $start = $request->start;
        $end = $request->end;
        $margin = $request->margin;
        $time_record = Time::where('date',$date)->first();

        if($time_record != null){
            if($margin != '8:30'){
                $time_record->$margin = '予約済';
            }
            $time_record->$start = '予約済';
            $time_record->$end = '予約済';
            $time_record->save();
        } else {
            $time = new Time;
            $time->date = $date;
            if($margin != '8:30'){
                $time->$margin = '予約済';
            }
            $time->$start = '予約済';
            $time->$end = '予約済';
            $time->save();
        }
    }

    public function send_mail(Request $request,$meeting)
    {
        $name = $request->name;
        $date_str = date('Y年n月j日',strtotime($request->date));
        $week = date('w',strtotime($request->date));
        $week_str = ['日','月','火','水','木','金','土'];
        $date = $date_str.'('.$week_str[$week].')';
        $start = $request->start;
        $join_url = $meeting['join_url'];
        $to = $request->email;
        Mail::to($to)->send(new Notification($name,$date,$start,$join_url));
    }

    public function create_access_token()
    {
        $client_key = env('CLIENT_KEY');
        $client_secret = env('CLIENT_SECRET');

        $signer = new Sha256;
        $time = time();
        $token = (new Builder())->issuedBy($client_key)
                                ->expiresAt($time + 3600)
                                ->getToken($signer,new Key($client_secret));

        return $token;
    }

    public function create_meeting(Request $request)
    {
        $date = $request->date;
        $time = $request->start;
        $duration = 60;
        $method = 'POST';
        $user_id = $this->get_users()['id'];
        $url = '/v2/users/'.$user_id.'/meetings';
        $token = $this->create_access_token();

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
        $res = $this->call_api($url,$client_params,$options,$method);

        return $res;
    }

    public function get_users()
    {
        $method = 'GET';
        $url = 'users';
        $token = $this->create_access_token();

        $client_params = [
            'base_uri' => $this->api_base_url,
        ];
        $options = [
            'headers' => [
                'Accept' => 'application/json, application/xml',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ]
        ];

        $users = $this->call_api($url,$client_params,$options,$method);

        return $users['users'][0];
    }

    public function call_api($url,$client_params,$options,$method)
    {
        $client = new Client($client_params);
        $res = $client->request($method,$url,$options)->getBody()->getContents();
        return json_decode($res,true);
    }
}
