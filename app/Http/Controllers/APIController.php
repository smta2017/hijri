<?php

namespace App\Http\Controllers;
// require_once './app/classes/hijri.class.php';


use Illuminate\Http\Request;
use App\Core\BaseController;

use App\datetime;
use App\Calendar;

class APIController extends Controller
{
    public $c_language;
    public $datTime;
    public $calender;

    function __construct()
    {
        // return "sameh";  
        $this->c_language = \App\Core::getCurrentLanguage();

        $this->datTime = new datetime(null, null, $this->c_language);
        $this->calender = new Calendar();
    }


    //this function used to convert dates
    function Converter($request)
    {
        if ($request->method != 'POST') {
            return false;
        }
        //extract the date from the request
        $day = (int)$request->data['day'];
        $month = (int)$request->data['month'];
        $year = (int)$request->data['year'];
        $from = $request->data['from'];
        $dt = new HijriDatetime(null, null, $request->data['lang']);

        $h = ($request->data['lang'] == 'ar') ? 'هـ' : '';
        $g = ($request->data['lang'] == 'ar') ? 'م' : '';
        $m = ($request->data['lang'] == 'ar') ? 'M' : 'F';
        if ($from == 'hijri') {
            $date = $this->calender->HijriToGregorian($year, $month, $day);
            $dt->setDate((int)$date['y'], (int)$date['m'], (int)$date['d']);

            $hijri_ar = $dt->format("D") . ' ' . $dt->format("_j _F _Y $h");
            $hijri_numeric = $dt->format("_j - _n - _Y $h");

            $georgian_ar = $dt->format("D") . ' ' . $dt->format("j $m Y $g");
            $georgian_numeric = $dt->format("j - n - Y $g");

            echo $this->JSONResponse([
                'hijri_ar' => $hijri_ar,
                'georgian_ar' => $georgian_ar,
                'georgian_numeric' => $georgian_numeric,
                'hijri_numeric' => $hijri_numeric
            ]);

            return true;
        } elseif ($from == 'georgian') {
            $date = $this->calender->GregorianToHijri($year, $month, $day);
            $dt->setDateHijri($date['y'], $date['m'], $date['d']);
            $hijri_ar = $dt->format("D") . ' ' . $dt->format("_j _F _Y $h");
            $hijri_numeric = $dt->format("_j - _n - _Y $h");


            $georgian_ar = $dt->format("D") . ' ' .  $dt->format("j $m Y $g");
            $georgian_numeric = $dt->format("j - n - Y $g");

            echo $this->JSONResponse([
                'hijri_ar' => $hijri_ar,
                'georgian_ar' => $georgian_ar,
                'georgian_numeric' => $georgian_numeric,
                'hijri_numeric' => $hijri_numeric
            ]);

            return true;
        } else {
            return false;
        }
    }

    //this function is used to get months list
    public function listMonths($request)
    {

        if ($request->method != 'POST') {
            return false;
        }

        $calendar = $request->data['calendar_type'];
        $dt = new datetime(null, null, $request->data['lang']);

        if ($calendar != 'hijri' && $calendar != 'georgian' && $calendar != 'solar') {
            return false;
        }

        $months = [];

        if ($calendar == 'hijri') {
            for ($month = 1; $month <= 12; $month++) {
                $dt->setDateHijri(1440, $month, 1);
                $months[$month] = $dt->format('_F');
            }
        } elseif ($calendar == 'georgian') {
            for ($month = 1; $month <= 12; $month++) {
                $dt->setDate(2019, $month, 1);
                if ($request->data['lang'] == 'en') {
                    $months[$month] = $dt->format('F');
                } else {
                    $months[$month] = $dt->format('M');
                }
            }
        } elseif ($calendar == 'solar') {
            if ($request->data['lang'] == 'ar') {
                $months = ['الميزان', 'العقرب', 'القوس', 'الجدي', 'الدلو', 'الحوت', 'الحمل', 'الثور', 'الجوزاء', 'السرطان', 'الأسد', 'السنبل'];
            } else {
                $months = ['Mizān (Libra)', '‘Aqrab (Scorpio)', 'Qaws (Sagittarius)', 'Jadi (Capricorn)', 'Dalvæ (Aquarius)', 'Hūt (Pisces)', 'Hamal (Aries)', 'Sawr (Taurus)', 'Jawzā (Gemini)', 'Saratān (Cancer)', 'Asad (Leo)', 'Sonbola (Virgo)'];
            }

            array_unshift($months, "tmp");
            unset($months[0]);
        }

        echo $this->JSONResponse($months);
        return true;
    }

    #used to get the hijri calender for specific year
    public function hijriCalendar($request)
    {
        $years = [];
        $days = __('main.days');
        //parent::getLanguageDays();
        $year = $request["year"];
        $month_inc = 1;
        $eccsc = "";
        for ($j = 0; $j <= 3; $j++) {
            for ($i = 0; $i <= 2; $i++) {
                $eccsc .= $this->buildMonth($month_inc, $year, FALSE, $days);
                // $years = array_merge($years, $build);
                $month_inc++;
            }
        }

        return $eccsc;
    }

    #used to get the georgian calendar for specific year
    public function georgianCalendar($request)
    {
        $years = [];
        $days =  __('main.days'); // parent::getLanguageDays();
        $year = $request["year"]; // (int) $request->data['year'];
        $month_inc = 1;
        $eccsc = "";
        for ($j = 0; $j <= 3; $j++) {
            for ($i = 0; $i <= 2; $i++) {
                $eccsc .= $this->buildMonthGeorgian($month_inc, $year, FALSE, $days);
                // $years = array_merge($years, $build);
                $month_inc++;
            }
        }
        return $eccsc;
    }




    public function buildMonth($month, $year, $outmonth = FALSE, $days, $onlyOne = null)
    {
        $dt = new datetime(null, null, $this->c_language);

        list($cday, $cmonth, $cyear) = explode('-', $dt->format('_j-_n-_Y'));


        $this->datTime->setDateHijri($year, $month + 1, 0);
        list($gm2, $gy2) = explode('-', $this->datTime->format("M-Y"));
        $this->datTime->setDateHijri($year, $month, 1);

        $m = ($this->c_language == 'ar') ? '_M' : '_F';
        list($start_wd, $month_name, $gm1, $gy1) = explode('-', $this->datTime->format("w-$m-M-Y"));

        $wd = array(0 => 1, 2, 3, 4, 5, 6, 0);
        $month_length = $this->calender->days_in_month($month, $year);
        if ($wd[$start_wd] > 0) {
            $this->datTime->modify("-" . $wd[$start_wd] . " day");
        }
        $g_symb = ($this->c_language == 'ar') ? 'م' : '';
        $h_symb = ($this->c_language == 'ar') ? 'هـ' : '';
        $years = array_unique([$gy1, $gy2]);

        $correspond =   $gm1 . (($gy2 != $gy1) ? " " . $gy1 : '') . (($gm2 != $gm1) ? "-" . $gm2 : '') . " " . $gy2 . "$g_symb ";

        $preced = ($this->c_language == 'ar') ? '/' . 'التقويم-الهجري' . '/' : '/hijri/';
        $month_link = (!$onlyOne) ? $preced . 'date/month/' . $month_name . '/' . $year : '#';

        $mondetail = '<div class="col-md-4">';
        list($hdx, $hmx, $hyx, $gdx, $gmx, $gy) = explode('-', $this->datTime->format("_j-_n-_Y-j-n-Y"));

        $classacv = '';
        if ($cmonth == $hmx + 1 && $cyear == $hyx) {
            $classacv = "active_month";
        } else {
            $classacv = '';
        }

        $mondetail .= '<div class="table_item ' . $classacv . '" >';
        $mondetail .= '<table >
                        <h4><a style="color:#000000;font-size: 20px; font-weight: bold;" href="' . $month_link . '">' . $month_name . ' ' . $year . $h_symb . '</a></h4>
                        <h5>' . $correspond . '</h5>
                <thead class="thead-light label">
            </thead> 
            <thead class="day-names" > 
            <tr>';
        foreach ($days as $day) {
            $mondetail .= "<th>$day</th>";
        }
        $mondetail .= '</tr></thead><tbody>';
        $dayw = 0;
        $ez_month = 0;
        $oky_ez = false;
        $semain = 0;
        $vvv = $this->datTime;
        do {
            list($hd, $hm, $hy, $gd, $gm, $gy) = explode('-', $this->datTime->format("_j-_n-_Y-j-n-Y"));
            if ($dayw == 0) {
                $mondetail .= "<tr>";
                $ez_month++;
                $semain++;
            }

            $class = '';
            if ($cday == $hd && $cmonth == $hm && $cyear == $hy) {
                $class = "active_day";
            } elseif ($hm > $month) {
                $class = 'exceed';
            }

            //$path = BASE_PATH;
            $path = ($this->c_language == 'ar') ? '/' . 'التقويم-الهجري' . '/' : '/hijri/';

            if ($onlyOne) {
                $onlyOne = str_replace('/', '', $onlyOne);

                if ($hm <= $month) {
                    if ($ez_month == 1 && $hd == 1) {
                        $oky_ez = true;
                    }
                    if ($oky_ez == true) {
                        if (($semain == 5 && $hd < 10) || ($semain == 6 && $hd < 10)) {
                            $mondetail .= "<td class='disabled' ></td>";
                        } else {
                            $mondetail .= "<td class='$class'><a href='/{$onlyOne}/date/$hd/$month_name/$year'>$hd<sub>$gd</sub></a></td>";
                        }
                    } else {
                        $mondetail .= "<td class='disabled' ></td>";
                    }
                } else {
                    $mondetail .= "<td class='disabled' ></td>";
                }
            } else {


                if ($hm <= $month) {
                    if ($ez_month == 1 && $hd == 1) {
                        $oky_ez = true;
                    }
                    if ($oky_ez == true) {
                        if (($semain == 5 && $hd < 10) || ($semain == 6 && $hd < 10)) {
                            $mondetail .= "<td class='disabled' ></td>";
                        } else {
                            $mondetail .= "<td class='$class'><a href='{$path}date/$hd/$month_name/$year'>$hd<sub>$gd</sub></a></td>";
                        }
                    } else {
                        $mondetail .= "<td class='disabled' ></td>";
                    }
                } else {
                    $mondetail .= "<td class='disabled' ></td>";
                }
            }



            if ($dayw == 6) {
                $mondetail .= "</tr>";
                $dayw = 0;
                if (($hm > $month) || ($hy > $year) || ($hm == $month && $hd == $month_length)) {
                    $mondetail .= "</tbody></table></div></div>";
                    $oky_ez = false;
                    break;
                }
            } else {
                $dayw++;
            }
            $this->datTime->modify("+1 day");
        } while (TRUE);

        return $mondetail;
    }


    public function buildMonthGeorgian($month, $year, $outmonth = FALSE, $days, $onlyOne = null)
    {
        $dt = new datetime(null, null, $this->c_language);


        list($cday, $cmonth, $cyear) = explode('-', $dt->format('j-n-Y'));

        $this->datTime->setDate($year, $month + 1, 0);
        list($gm2, $gy2) = explode('-', $this->datTime->format("_M-_Y"));

        $this->datTime->setDate($year, $month, 1);
        $m = ($this->c_language == 'ar') ? 'M' : 'F';
        list($start_wd, $month_name, $gm1, $gy1) = explode('-', $this->datTime->format("w-$m-_M-_Y"));

        $wd = array(0 => 1, 2, 3, 4, 5, 6, 0);
        $month_length = $this->calender->days_in_month($month, $year);
        if ($wd[$start_wd] > 0) {
            $this->datTime->modify("-" . $wd[$start_wd] . " day");
        }
        $g_symb = ($this->c_language == 'ar') ? 'م' : '';
        $h_symb = ($this->c_language == 'ar') ? 'هـ' : '';
        $years = array_unique([$gy1, $gy2]);
        $correspond = "(" . $gm1 . (($gy2 != $gy1) ? " " . $gy1 : '') . (($gm2 != $gm1) ? "-" . $gm2 : '') . " " . $gy2 . "$g_symb)";

        $month_link = (!$onlyOne) ? 'date/month/' . $month_name . '/' . $year : '#';

        $mondetail = '<div class="col-md-4">';
       
        list($hdx, $hmx, $hyx, $gdx, $gmx, $gyx) = explode('-', $this->datTime->format("j-n-Y-_j-_n-_Y"));
        

        $classacv = '';
      
        if ($cmonth == $hmx+1 && $cyear == $hyx) {
            $classacv = "active_month";
        }

        $mondetail .= '<div class="table_item ' . $classacv . '" >';
        $mondetail .= '<table>
                            <thead class="thead-light label">
                            <h4><a style="color:#000000;font-size: 20px; font-weight: bold;" href="' . $month_link . '">' . $month_name . ' ' . $year . $h_symb . '</a></h4>
                            <h5>' . $correspond . '</h5>
                        </thead>
                        <thead class="day-names"
                        <tr>';
        foreach ($days as $day) {
            $mondetail .= "<th>$day</th>";
        }
        $mondetail .= '</tr></thead><tbody>';
        $dayw = 0;
        $ez_month = 0;
        $oky_ez = false;
        do {

            //          list($hd, $hm, $hy, $gd, $gm, $gy) = explode('-', $d->format("_j-_n-_Y-j-n-Y"));

            list($hd, $hm, $hy, $gd, $gm, $gy) = explode('-', $this->datTime->format("j-n-Y-_j-_n-_Y"));
            if ($dayw == 0) {
                $mondetail .= "<tr>";
                $ez_month++;
            }
            $class = '';

            if ($cday == $hd && $cmonth == $hm && $cyear == $hy) {
                $class = "active_day";
            }

            $path = "hijricalendars.test";
            if ($onlyOne) {
                $onlyOne = str_replace('/', '', $onlyOne);
                if ($hm <= $month) {
                    if ($ez_month == 1 && $hd == 1) {
                        $oky_ez = true;
                    }
                    if ($oky_ez == true) {
                        $mondetail .= "<td class='$class'><a href='{$path}{$onlyOne}/date/$hd/$month_name/$year'>$hd<sub>$gd</sub></a></td>";
                    } else {
                        $mondetail .= "<td class='disabled' ></td>";
                    }
                } else {
                    $mondetail .= "<td class='disabled' ></td>";
                }
            } else {
                if ($hm <= $month) {
                    if ($ez_month == 1 && $hd == 1) {
                        $oky_ez = true;
                    }
                    if ($oky_ez == true) {
                        $mondetail .= "<td class='$class'><a href='date/$hd/$month_name/$year'>$hd<sub>$gd</sub></a></td>";
                    } else {
                        $mondetail .= "<td class='disabled' ></td>";
                    }
                } else {
                    $mondetail .= "<td class='disabled' ></td>";
                }
            }


            if ($dayw == 6) {
                $mondetail .= "</tr>";
                $dayw = 0;
                if (($hm > $month) || ($hy > $year) || ($hm == $month && $hd == $month_length)) {
                    $mondetail .= "</tbody></table></div></div>";
                    break;
                }
            } else {
                $dayw++;
            }
            $this->datTime->modify("+1 day");
        } while (TRUE);
        return $mondetail;
    }
}
