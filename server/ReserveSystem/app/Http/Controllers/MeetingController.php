<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Builder;
use Validator;

class MeetingController extends Controller
{
    public $api_base_url = 'https://api.zoom.us/v2/';

    public function reserve()
    {
        return view('reserve/reserve');
    }

    public function check(Request $request)
    {
        $input = $request->all();

        $rules = [
            'time' => 'required',
            'duration' => 'required'
        ];
        Validator::make($input,$rules)->validate();

        return view('reserve/check')->with('input',$input);
    }

    public function reserveMeeting(Request $request)
    {
        $meeting = $this->create_meeting($request);

        return view('reserve/reserve_meeting')->with('meeting',$meeting);
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
        $time = $request->time;
        if(isset($request->topic)){
            $topic = $request->topic;
        } else {
            $topic = "";
        }
        $method = 'POST';
        $user_id = $this->get_users()['id'];
        $url = '/v2/users/'.$user_id.'/meetings';
        $token = $this->create_access_token();

        $params = [
            'topic' => $topic,
            'type' => 2,
            'start_time' => $date.'T'.$time.':00',
            'duration' => $request->duration,
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
