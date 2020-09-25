<?php

/**
 * Created by PhpStorm.
 * User: inejih
 * Date: 03/09/2019
 * Time: 00:47
 */

namespace App;


use App\datetime;
use App\Calendar;

class Today
{
    public $dateTime;
    public $calendar;
    public $c_language;

    function __construct()
    {
        $this->c_language =  \App\Core::getCurrentLanguage();
        // require_once DIR_BASE . 'external/Hijri/hijri.class.php';
        $this->calendar = new Calendar();
        $this->dateTime = new datetime(null, null, $this->c_language, $this->calendar);
    }


    public function setSpecificDay($day, $month, $year, $type)
    {
        if ($type == 'hijri') {
            return $this->dateTime->setDateHijri($year, $month, $day);
        } elseif ($type == 'georgian') {
            return  $this->dateTime->setDate($year, $month, $day);
        } else {
            return false;
        }
    }

    public function getCurrentDate($format = 'hijri', $only = false)
    {

        if ($format == 'hijri') {
            $h = ($this->c_language == 'ar') ? 'هـ' : '';

            if ($only == 'day') {
                return $this->dateTime->format('l');
            } elseif ($only == 'numeric') {
                return $this->dateTime->format("_j - _n - _Y $h");
            }

            $hijri = $this->dateTime->format("_j _F _Y $h");

            return $hijri;
        } elseif ($format == 'georgian') {
            $g = ($this->c_language == 'ar') ? 'م' : '';

            if ($only == 'day') {
                return $this->dateTime->format('J');
            } elseif ($only == 'numeric') {
                return $this->dateTime->format("j - n - Y $g");
            }
            $m = ($this->c_language == 'ar') ? 'M' : 'F';
            $georgian = $this->dateTime->format("j $m Y $g");

            return $georgian;
        } else {
            return false;
        }
    }
}
