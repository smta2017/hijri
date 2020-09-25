<?php

/**
 * Created by PhpStorm.
 * User: inejih
 * Date: 25/08/2019
 * Time: 01:21
 */

namespace App;

use App\Http\Controllers\APIController;
use App\Core\Request;
use App\Http\Controllers\DefaultController;

class  Core
{

    private $specialType;

    function __construct()
    {
        date_default_timezone_set('Asia/Aden');
        require_once DIR_BASE . 'external/Hijri/hijri.class.php';
    }

    //return the route English name (AR ----> EN)
    public static function mapingRoute($route, $rlang = null)
    {

        global $routes_map;

        $key = array_search(urldecode($route), $routes_map);
        if ($key) {
            if ($rlang) {
                $_SESSION['lang'] = 'ar';
            }
            return $key;
        } else {
            if ($rlang && $route != '/') {
                $_SESSION['lang'] = 'en';
            } elseif ($route == '/') {
                $_SESSION['lang'] = 'ar';
            }
            return $route;
        }
    }

    //return the Arabic route name (EN -> AR)
    public static function mapingRouteReverse($routes_name)
    {
        global $routes_map;

        /*    #to be changed later
            $req = new Request();
            $specificDay = (new Core())->specialDayRoute($req);

            if($specificDay){
                return "";
            }
    */
        if ($routes_name == "") {
            return "";
        }

        return $routes_map[$routes_name];
    }


    //used to check if routes exists !
    private function routeExists(Request $request, $routes)
    {

        $route = self::mapingRoute($request->getRoute(), true);

        $bool = array_key_exists($route, $routes);
        if ($bool) {
            return true;
        }


        return false;
    }

    //handle routes
    public function HandleRequest($request)
    {


        //to handle the language switcher
        $this->handleLanguage($request);

        //to handle day in separate page
        if ($this->specialMonthRoute($request)) {

            $controller = new DefaultController();
            $decRequest = urldecode($request->getRoute());
            return $controller->specialeMonth($decRequest, $this->specialType);
        }

        //to handle day in separate page
        if ($this->specialDayRoute($request)) {
            $controller = new DefaultController();
            $decRequest = urldecode($request->getRoute());

            return $controller->specialeDate($decRequest, $this->specialType);
        }

        //to handle year in sperate page
        if ($this->specialYearRoute($request)) {
            return 0;
        }

        $routes_list = $this->getRoutesList();

        if (!$this->routeExists($request, $routes_list)) {
            $controller = new DefaultController();
            return $controller->NotFound();
        }

        $route  = self::mapingRoute($request->getRoute());

        $route_info = $routes_list[$route];

        $className = 'App\\Controllers\\' . $route_info['controller'];
        $controller = new $className;

        if ($route_info['with_data'] == "true") {
            return $controller->{$route_info['action']}($request);
        } else {
            return $controller->{$route_info['action']}();
        }
    }

    #return the routes list
    private function getRoutesList()
    {
        $json = file_get_contents(DIR_BASE . "config/routes.json");
        $routes_list = json_decode($json, true);

        return $routes_list;
    }

    #return the current site language
    static function getCurrentLanguage()
    {
        $current_lang = \App::getLocale();
        if ($current_lang == 'ar') {
            return 'ar';
        } elseif ($current_lang == 'en') {
            return 'en';
        }else{
            return 'en';
        }
    }

    #switch language
    private function handleLanguage($request)
    {

        if ($request->method == 'POST' && isset($request->data['switch_lang'])) {
            if (\App\Core::getCurrentLanguage() == 'ar') {
                $_SESSION['lang'] = 'en';
                return;
            } elseif (\App\Core::getCurrentLanguage() == 'en') {
                $_SESSION['lang'] = 'ar';
                return;
            } else {
                return false;
            }
        }
    }

    //for displaying one day in page
    private function specialDayRoute($request)
    {

        if (preg_match('#/hijri/date/#', $request->getRoute()) || preg_match("#" . self::mapingRouteReverse('/hijri') . "/date/#", urldecode($request->getRoute()))) {

            $this->specialType = 'hijri';
            return true;
        }

        if (preg_match('#/georgian/date/#', $request->getRoute()) || preg_match("#" . self::mapingRouteReverse('/georgian') . "/date/#", urldecode($request->getRoute()))) {
            $this->specialType = 'georgian';
            return true;
        }

        return false;
    }

    //handling requests for calendar page
    private function specialYearRoute(Request $request)
    {
        $route = urldecode($request->getRoute());
        $hijri = '/hijri';
        $hijri_ar = urldecode(self::mapingRouteReverse($hijri));
        $georgian = '/georgian';
        $georgian_ar = urldecode(self::mapingRouteReverse($georgian));

        $hijri_ar_route = preg_match("#$hijri_ar\/.{4}$#", $route);
        $hijri_route = preg_match("#$hijri\/.{4}$#", $route);

        if ($hijri_ar_route || $hijri_route) {

            if ($hijri_route) {
                $_SESSION['lang'] = 'en';
            }
            if ($hijri_ar_route) {
                $_SESSION['lang'] = 'ar';
            }

            //get current path
            $path = explode('/', $route);
            unset($path[0]);
            $path = array_values($path);
            $year = (int) $path[1];
            if (self::getCurrentLanguage() == 'ar') {
                $path[0] = self::mapingRoute('/' . $path[0]);
                $path_en = implode("/", $path);
                (new DefaultController())->hijriCalendar(['year' => $year, 'path_en' => $path_en]);
                return true;
            } elseif (self::getCurrentLanguage() == 'en') {
                $path[0] = self::mapingRouteReverse('/' . $path[0]);
                $path_ar = implode("/", $path);
                (new DefaultController())->hijriCalendar(['year' => $year, 'path_ar' => $path_ar]);
                return true;
            }
        }

        $georgian_ar_route = preg_match("#$georgian_ar\/.{4}$#", $route);
        $georgian_route = preg_match("#$georgian\/.{4}$#", $route);
        if ($georgian_ar_route || $georgian_route) {

            if ($georgian_route) {
                $_SESSION['lang'] = 'en';
            }
            if ($georgian_ar_route) {
                $_SESSION['lang'] = 'ar';
            }


            $path = explode('/', $route);
            unset($path[0]);
            $path = array_values($path);
            $year = (int) $path[1];
            if (self::getCurrentLanguage() == 'ar') {
                $path[0] = self::mapingRoute('/' . $path[0]);
                $path_en = implode("/", $path);

                (new DefaultController())->georgianCalendar(['year' => $year, 'path_en' => $path_en]);
                return true;
            } elseif (self::getCurrentLanguage() == 'en') {
                $path[0] = self::mapingRouteReverse('/' . $path[0]);
                $path_ar = implode("/", $path);

                (new DefaultController())->georgianCalendar(['year' => $year, 'path_ar' => $path_ar]);
                return true;
            }
        }
    }

    #month route

    private function specialMonthRoute($request)
    {

        if (preg_match('#hijri/date/month#', $request->getRoute()) || preg_match("#" . self::mapingRouteReverse('/hijri') . "/date/month#", urldecode($request->getRoute()))) {
            $this->specialType = 'hijri';
            return true;
        }

        if (preg_match('#georgian/date/month#', $request->getRoute()) || preg_match("#" . self::mapingRouteReverse('/georgian') . "/date/month#", urldecode($request->getRoute()))) {
            $this->specialType = 'georgian';
            return true;
        }

        return false;
    }
}
