<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DefaultController;

class BaseController extends Controller
{

    static function render($filename, $params = [])
    {
        ob_start();
        if (!empty($filename)) {

            $lang = self::getLanguageData();
            $days = self::getLanguageDays();
            $data = $params;
            $direction = (\App\Core::getCurrentLanguage() == 'ar') ? 'rtl' : 'ltr';
            $request = new Request();

            if (file_exists(DIR_BASE . 'pages/' . $filename . '.html.php')) {
                require_once DIR_BASE . 'pages/' . $filename . '.html.php';
                $content2 = ob_get_clean();
            } else {
                $home = BASE_PATH;
                header("location: $home");
            }

            // require the default layout
            require_once DIR_BASE . 'pages/layout/template.html.php';
        }
    }



    static public function getLanguageData()
    {
        global $lang;
        if (\App\Core::getCurrentLanguage() == 'ar') {
            require DIR_BASE . 'languages/ar.php';
        } else {
            require DIR_BASE . 'languages/en.php';
        }

        return $lang;
    }

    static public function getLanguageDays()
    {
        global $days;
        if (\App\Core::getCurrentLanguage() == 'ar') {
            require DIR_BASE . 'languages/ar.php';
        } else {
            require DIR_BASE . 'languages/en.php';
        }

        return $days;
    }
    static public function getLanguageDaysFull()
    {
        global $days_full;
        if (\App\Core::getCurrentLanguage() == 'ar') {
            require DIR_BASE . 'languages/ar.php';
        } else {
            require DIR_BASE . 'languages/en.php';
        }

        return $days_full;
    }

    public function JSONResponse($data)
    {
        header('Content-type: application/json');
        header('Access-Control-Allow-Origin: *');
        return json_encode($data);
    }
}
