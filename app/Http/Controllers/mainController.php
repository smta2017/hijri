<?php

namespace App\Http\Controllers;

use App\Today;
use Illuminate\Http\Request;
use App\Http\Controllers\DefaultController;

class mainController extends Controller
{
    public function changeLocale(Request $request)
    {
        $this->validate($request, ['locale' => 'required|in:ar,en']);

        // session()->push('local',$request->locale);
        session(['local' => $request->locale]);
        $value = session('local');

        // return redirect()->back();
        return redirect('/');
    }

    public function changeyear(Request $request)
    {
        $this->validate($request, ['year' => 'required']);

        // session()->push('local',$request->locale);
        session(['year' => $request->year]);
        $value = session('year');

        // return redirect()->back();
        return redirect('/');
    }

    public function index()
    {
        \App::setLocale(session('local'));

        // \App::setLocale("en");
        $date = new  Today();

        $api = new  APIController();
        $request = ["year" => "1442"];
        if (session('year') != '') {
            $request = ["year" => "2020"];
        }
        $tyear = $request['year'];
        $calender  = $api->hijriCalendar($request);

        $surfix = (\App::isLocale("ar")) ? 'هـ' : '';
        $thedate['type'] = 'hijri';
        if ($thedate['type'] == 'hijri') {
            $desk = __('main.today_date') . $date->getCurrentDate() . ' ' . __('main.correspond_to') . $date->getCurrentDate('georgian') . __('main.based_on');
        } else {
            $desk = __('main.today_date') . $date->getCurrentDate('georgian') . ' ' . __('main.correspond_to') . $date->getCurrentDate() . __('main.based_on');
        }
        $numeric_date = $date->getCurrentDate('hijri', 'numeric'); //$date->setSpecificDay($day, $mon, $year, "hijri");

        $the_date = $date->getCurrentDate('hijri'); //$date->setSpecificDay($day, $mon, $year, "hijri");

        $the_day = $date->getCurrentDate('hijri', 'day');


        $full_date =  $date->getCurrentDate('hijri', 'day') . ' ' . $the_date;

        $title =  __('main.hijri_page_title');
        $page_title = __('main.day') . $full_date;

        $keywords = "hijri, هجري, georgian, convert, تحويل التاريخ, months, ramadan, umm alqura, أم القرى,تقويم," . $tyear;
        $the_date_go = $date->getCurrentDate('georgian'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $numeric_date_go = $date->getCurrentDate('georgian', 'numeric'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $full_date_go =   $the_date_go;

        return view("welcome", compact('calender', 'keywords', 'tyear', 'title', 'page_title', 'desk',   'numeric_date_go', 'full_date_go', 'full_date', 'numeric_date', 'the_date', 'the_day', 'surfix'));
    }

    public function gorgianCalender()
    {
        \App::setLocale(session('local'));

        // \App::setLocale("en");
        $date = new  Today();

        $api = new  APIController();
        $request = ["year" => "2020"];
        $tyear = $request['year'];
        $calender  = $api->georgianCalendar($request);

        $surfix = (\App::isLocale("ar")) ? 'هـ' : '';
        $thedate['type'] = 'hijri';
        if ($thedate['type'] == 'hijri') {
            $desk = __('main.today_date') . $date->getCurrentDate() . ' ' . __('main.correspond_to') . $date->getCurrentDate('georgian') . __('main.based_on');
        } else {
            $desk = __('main.today_date') . $date->getCurrentDate('georgian') . ' ' . __('main.correspond_to') . $date->getCurrentDate() . __('main.based_on');
        }
        $numeric_date = $date->getCurrentDate('hijri', 'numeric'); //$date->setSpecificDay($day, $mon, $year, "hijri");

        $the_date = $date->getCurrentDate('hijri'); //$date->setSpecificDay($day, $mon, $year, "hijri");

        $the_day = $date->getCurrentDate('hijri', 'day');


        $full_date =  $date->getCurrentDate('hijri', 'day') . ' ' . $the_date;

        $title = $full_date . '  -  '  . __('main.hijri_page_title');
        $page_title = __('main.day') . $full_date;

        $the_date_go = $date->getCurrentDate('georgian'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $numeric_date_go = $date->getCurrentDate('georgian', 'numeric'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $full_date_go =   $the_date_go;

        $keywords = "hijri, هجري, georgian, convert, تحويل التاريخ, months, ramadan, umm alqura, أم القرى,تقويم," . $tyear;


        return view("welcome-g", compact('calender', 'keywords', 'tyear', 'title', 'page_title', 'desk',   'numeric_date_go', 'full_date_go',    'full_date', 'numeric_date', 'the_date', 'the_day', 'surfix'));
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


    public function single($calender, $day = 0, $mon = 0, $year = 0)
    {
        \App::setLocale(session('local'));

        // \App::setLocale("en");

        $date = new  Today();

        if ($day == 'month') {
            return $calender . '/' . $mon . '/' . $year;
        }
        $specialeDate = new DefaultController();
        $thedate = $specialeDate->specialeDate($day . '/' . $mon . '/' . $year, 'hijri');
        // return $thedate;
        $request = ["year" => "1442"];
        $tyear = $request['year'];
        $keywords = "hijri, هجري, georgian, convert, تحويل التاريخ, months, ramadan, umm alqura,أم القرى,تقويم," . $date->getCurrentDate();
        $surfix = (\App::isLocale("ar")) ? 'هـ' : '';

        if ($thedate['type'] == 'hijri') {
            $desk = __('main.today_date') . $date->getCurrentDate() . ' ' . __('main.correspond_to') . $date->getCurrentDate('georgian') . __('main.based_on');
        } else {
            $desk = __('main.today_date') . $date->getCurrentDate('georgian') . ' ' . __('main.correspond_to') . $date->getCurrentDate() . __('main.based_on');
        }

        $date->setSpecificDay($thedate["day"], $thedate["month"], $thedate["year"], "hijri");

        $the_date = $date->getCurrentDate('hijri'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $the_date_go = $date->getCurrentDate('georgian'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $numeric_date = $date->getCurrentDate('hijri', 'numeric'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $numeric_date_go = $date->getCurrentDate('georgian', 'numeric'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $full_date =  $date->getCurrentDate('hijri', 'day') . ' ' . $the_date;
        $full_date_go =   $the_date_go;
        $the_day = $date->getCurrentDate('hijri', 'day');

        $title = $full_date . '  -  '  . __('main.hijri_page_title');
        $page_title = __('main.day') . $full_date;


        return view('single-day', compact('keywords', 'tyear', 'title', 'page_title', 'desk',    'full_date', 'numeric_date', 'numeric_date_go', 'full_date_go', 'the_date', 'the_day', 'surfix'));
    }

    public function single2($day = 0, $mon = 0, $year = 0)
    {
        \App::setLocale(session('local'));

        // \App::setLocale("en");

        $date = new  Today();

        if ($day == 'month') {
            // return $calender . '/' . $mon . '/' . $year;
        }
        $specialeDate = new DefaultController();
        $thedate = $specialeDate->specialeDate($day . '/' . $mon . '/' . $year, 'georgian');
        // return $thedate;
        $request = ["year" => "1442"];
        $tyear = $request['year'];
        $keywords = "georgian, هجري, georgian, convert, تحويل التاريخ, months, ramadan, umm alqura,أم القرى,تقويم," . $date->getCurrentDate();
        $surfix = (\App::isLocale("ar")) ? 'هـ' : '';

        if ($thedate['type'] == 'georgian') {
            $desk = __('main.today_date') . $date->getCurrentDate() . ' ' . __('main.correspond_to') . $date->getCurrentDate('georgian') . __('main.based_on');
        } else {
            $desk = __('main.today_date') . $date->getCurrentDate('georgian') . ' ' . __('main.correspond_to') . $date->getCurrentDate() . __('main.based_on');
        }

        $date->setSpecificDay($thedate["day"], $thedate["month"], $thedate["year"], "georgian");

        $the_date = $date->getCurrentDate('georgian'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $numeric_date = $date->getCurrentDate('georgian', 'numeric'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $the_day = $date->getCurrentDate('hijri', 'day');
        $full_date =  $the_day . " " .  $date->getCurrentDate('georgian');

        $the_date_go = $date->getCurrentDate('georgian'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $numeric_date_go = $date->getCurrentDate('georgian', 'numeric'); //$date->setSpecificDay($day, $mon, $year, "hijri");
        $full_date_go =   $the_date_go;

        $title = $full_date . '  -  '  . __('main.georgian_page_title');
        $page_title = __('main.day') . $full_date;


        return view('single-day2', compact('keywords', 'tyear', 'title', 'page_title', 'desk',  'numeric_date_go', 'full_date_go',   'full_date', 'numeric_date', 'the_date', 'the_day', 'surfix'));
    }
}
