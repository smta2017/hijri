<?php

namespace App\Http\Controllers;

use App\Today;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class testController extends Controller
{
    public function index()
    {
        \App::setLocale("en");

        $api = new  APIController();
        // $request_calendar = new RequestController();
        $request = ["year" => "1442"];
        $calender  = $api->hijriCalendar($request);
        // return $calender;
        //  return($calender);
        return view("welcome", compact('calender'));
        // $api->georgianCalendar('');
    }

    public function index2(Request $request)
    {
        // return $request->path();
        $date = new  Today();
        $dtdt =  $date->setSpecificDay(1, 10, 2020, "hijri");
        return $date->getCurrentDate('hijri', 'day');
        return $date->getCurrentDate();
        return ($dtdt);
    }


    public function index3($calender, $day, $mon, $year)
    {
        return $calender . ',' . $day . ',' . $mon . ',' . $year;
        return "totot";
    }
    public function index4()
    {
        return "dododo";
    }
}
