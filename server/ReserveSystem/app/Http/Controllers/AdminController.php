<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zoom;

class AdminController extends Controller
{
    public function getIndex()
    {
        $now = date('Y-m-d');
        $record = Zoom::where('date',$now)->get();

        return view('admin/admin')->with('list',$record);
    }

    public function guestInfo()
    {
        $guest = Zoom::select('name','tel','email')->distinct()->get();

        return view('admin/guest')->with('list',$guest);
    }

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
