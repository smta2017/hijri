<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Core;
use App\Model\QuoteManager;
use App\datetime;
use App\Calendar;

class DefaultController extends Controller
{

    public $c_language;

    public function __construct()
    {
        $this->c_language = \App\Core::getCurrentLanguage();
    }

    //render site hompage
    public function index()
    {
        $data = ['path_en' => '/en', 'path_ar' => '/'];

        $calendar_year_hijri = (int) (new datetime())->format("_Y");
        $data['year'] = $calendar_year_hijri;

        //$path = \App\Core::mapingRouteReverse('/hijri') . "/$calendar_year_hijri";

        return $this->render('basic/hijri', $data);

        // header("location: $path");
    }

    //render site hompage
    public function TodayDate()
    {
        return $this->render('home');
    }

    //render site english hompage for SEO
    public function EnglishVersion()
    {
        $data = ['path_en' => '/en', 'path_ar' => '/'];

        $calendar_year_hijri = (int) (new datetime())->format("_Y");
        $data['year'] = $calendar_year_hijri;
        return $this->render('basic/hijri', $data);
    }

    #to render login page
    public function Login()
    {
        $this->render("login/login");
    }
    public function Logout()
    {
        unset($_SESSION['user_logged_in']);
        header("location: /login");
    }

    public function SaveData($request)
    {
        global $db;

        $db->where('id', 1);

        $quote = $db->update('quotes', $request->data);

        if ($quote) {
            $_SESSION['system_message'] = 'Successfully saved !';
            header("location: /hijricpanel");
        }
    }

    private function isAuth()
    {
        return $_SESSION['user_logged_in'] ? true : false;
    }


    public function LoginHome($request = null)
    {
        global $db;

        //check if user is looged in
        if (empty($request->data)) {
            if (!$this->isAuth()) {
                $_SESSION['system_message'] = 'you\'ve to logged in first !';
                header("location: /login");
            }
        } else {
            $user = $request->data['username'];
            $pass = $request->data['password'];

            $db->where("username", $user);
            $db->where("password", md5($pass));

            $user = $db->getOne("users");

            if ($user) {
                $_SESSION['user_logged_in'] = true;
            } else {
                $_SESSION['system_message'] = 'error login or password !';
                header("location: /login");
            }
        }

        require_once DIR_BASE . 'pages/login/home.html.php';
    }

    #to render register page
    public function Register()
    {
        $this->render("login/register");
    }

    #non existed  routes
    public function NotFound()
    {
        $basePath = BASE_PATH;

        header("location: $basePath");
    }

    #to render the hijri calendar page
    public function hijriCalendar($data)
    {
        $this->render('basic/hijri', $data);
    }

    #to render the georgian calendar page
    public function georgianCalendar($data)
    {
        $this->render('basic/georgian', $data);
    }

    #to render the solar calendar page
    public function solarCalendar()
    {
        $this->render('basic/solar');
    }

    #render ramadan page
    public function Ramadan()
    {
        $data = [];
        $quoteManager = new QuoteManager();

        $data['ramadan'] = $quoteManager->getQuote(1);
        $data['year'] = '1996';
        $this->render('basic/ramadan', $data);
    }

    #render converter page
    public function Converter()
    {
        $this->render('basic/converter');
    }
    #render the months page
    public function Months()
    {
        $this->render('basic/months');
    }

    //    public function Calendar()
    public function specialeDate($dec, $type)
    {
        $calendar = new Calendar();
        $dateTime = new datetime(null, null, $this->c_language, $calendar);

        $months = [];

        $months_translate = [];

        $mg = ($this->c_language == 'ar') ? 'M' : 'F';
        $mh = ($this->c_language == 'ar') ? '_M' : '_F';

        for ($i = 1; $i <= 12; $i++) {
            $dateTime->setDate(2019, $i, 1);
            $d_georgian = $dateTime->format($mg);
            $dateTime->setDateHijri(1441, $i, 1);
            $d_hijri = $dateTime->format($mh);

            $months[$d_georgian] = $i;
            $months[$d_hijri] = $i;
        }

        $dayParts = explode('/', $dec);
        // unset($dayParts[0]);
        // $dayParts = array_values($dayParts);
        $path = $dayParts;
        // unset($dayParts[0], $dayParts[1]);
        $dayParts = array_values($dayParts);

        list($day, $month, $year) = $dayParts;
        $month = $months[$month];
        $day = (int) $day;
        $year = (int) $year;

        if (!isset($month) || $year == 0 || $day == 0) {
            header("location: /");
        }

        $full_date = [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'type' => $type
        ];



        if ($this->c_language == 'ar') {
            // $path[0] = Core::mapingRoute('/' . $path[0]);

            $dateT = new datetime(null, null, 'en', $calendar);

            if ($type == 'hijri') {
                $dateT->setDateHijri($year, $month, $day);
                // $path[3] = $dateT->format('_F');
            } elseif ($type == 'georgian') {
                $dateT->setDate($year, $month, $day);
                // $path[3] = $dateT->format('F');
            }

            $path_en = implode("/", $path);
            $full_date['path_en'] = $path_en;

            return $full_date;
            // self::render('basic/single-date', $full_date);
        } else {
            // $path[0] = Core::mapingRouteReverse('/' . $path[0]);

            $dateT = new datetime(null, null, 'ar', $calendar);

            if ($type == 'hijri') {
                $dateT->setDateHijri($year, $month, $day);
                // $path[3] = $dateT->format('_F');
            } elseif ($type == 'georgian') {
                $dateT->setDate($year, $month, $day);
                // $path[3] = $dateT->format('M');
            }

            $path_ar = implode("/", $path);

            $full_date['path_ar'] = $path_ar;

            return $full_date;
            // self::render('basic/single-date', $full_date);
        }
    }

    public function specialeMonth($decRequest, $specialType)
    {

        $calendar = new Calendar();
        $dateTime = new datetime(null, null, $this->c_language, $calendar);

        $months = [];

        $mg = ($this->c_language == 'ar') ? 'M' : 'F';
        $mh = ($this->c_language == 'ar') ? '_M' : '_F';

        for ($i = 1; $i <= 12; $i++) {
            $dateTime->setDate(2019, $i, 1);
            $d_georgian = $dateTime->format($mg);
            $dateTime->setDateHijri(1441, $i, 1);
            $d_hijri = $dateTime->format($mh);

            $months[$d_georgian] = $i;
            $months[$d_hijri] = $i;
        }

        $dayParts = explode('/', $decRequest);
        unset($dayParts[0]);
        $dayParts = array_values($dayParts);
        $path = $dayParts;
        unset($dayParts[0], $dayParts[1], $dayParts[2]);
        $dayParts = array_values($dayParts);


        list($month, $year) = $dayParts;
        $month = $months[$month];
        $year = (int)$year;

        if (!isset($month) || $year == 0) {
            header("location: /");
        }

        $full_date = [
            'month' => $month,
            'year' => $year,
            'type' => $specialType
        ];

        if ($this->c_language == 'ar') {
            $cal_trans = $path[0];

            $path[0] = Core::mapingRoute('/' . $path[0]);
            $dateT = new datetime(null, null, 'en', $calendar);

            if ($specialType == 'hijri') {
                $dateT->setDateHijri($year, $month, 1);
                $path[3] = $dateT->format('_F');
            } elseif ($specialType == 'georgian') {
                $dateT->setDate($year, $month, 1);
                $path[3] = $dateT->format('F');
            }

            $path_en = implode("/", $path);

            $full_date['path_en'] = $path_en;
            $full_date['cal_trans'] = $cal_trans;
        } elseif ($this->c_language == 'en') {
            $cal_trans = $path[0];

            $path[0] = Core::mapingRouteReverse('/' . $path[0]);

            $dateT = new datetime(null, null, 'ar', $calendar);

            if ($specialType == 'hijri') {
                $dateT->setDateHijri($year, $month, 1);
                $path[3] = $dateT->format('_F');
            } elseif ($specialType == 'georgian') {
                $dateT->setDate($year, $month, 1);
                $path[3] = $dateT->format('M');
            }

            $path_ar = implode("/", $path);
            $full_date['path_ar'] = $path_ar;
            $full_date['cal_trans'] = $cal_trans;
        }

        self::render('basic/single-month', $full_date);
    }
}
