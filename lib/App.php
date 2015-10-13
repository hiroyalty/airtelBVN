<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of App
 *
 * @author gbolaga
 */
include_once 'bvn.php';

class App {

    //put your code here


    public static function setNormalHeaders($headers) {
        header("Expires: -1");
        #header("Content-Type: UTF-8");
        header("Path: " . $_SERVER['PHP_SELF']);
        header("Cache-Control: max-age=0");
        # set custom headers
        foreach ($headers as $key => $value) {
            header("$key:$value");
        }
    }

    public static function logRequestToFile($msg) {
        #$date_time = date("Y-m-d h:i:s");
        #$logpath = '/var/www/html/nsl/';
        #$logFile = "call.log";
        //$log = "$date_time >> $msg";
        //$logFile = "/var/www/html/flares/flares.log";
        $logfile = "flares.log";
        $fp = fopen($logFile, 'a+');
        fputs($fp, "Logging USSD Request: $msg\n");
        fclose($fp);
        return TRUE;
    }

    public static function requestToArray() {
        $log = "";
        if (isset($_REQUEST)) {
            foreach ($_REQUEST as $key => $value) {
                $log.= "$key : $value,";
            }
            #App::logRequesToFile($log);
        }
        return $log;
    }

    public static function getResponse() {
        include_once 'tbvn.php';
        $input = trim($_REQUEST['INPUT']) ? $_REQUEST['INPUT'] : '565';
        $msisdn = isset($_REQUEST['msisdn']) ? trim($_REQUEST['msisdn']) : '2348132614337';
        $code = $_REQUEST['code'] ? $_REQUEST['code'] : '565'; //always the same
        $sessionid = isset($_REQUEST['sessionid']) ? $_REQUEST['sessionid'] : time();
        // find out what ussd code is running and map it to the right app
        /*
         * 776 is mhealth, 565 is mmarket
         */
        switch ($code) {
            case '565':
                include_once 'tbvn.php';
                try {
                    $content = Tbvn::getContent($input, $sessionid, $msisdn);
                } catch (Exception $e) {
                    $content = "BVN App";
                }
                break;

            /*case '776':
                try {
                    $content = self::putText($input);
                } catch (Exception $e) {
                    $content = "BVN App";
                }
                break; */
            
            default:
                break;
        }

        #$logFile = "/var/www/html/flares/flares.log";
        //$txt = App::requestToArray();
        #echo $txt;
        //App::logRequestToFile($txt);
        return $content;
        #return $txt;
    }

    public static function setCleanUpHeaders() {
        header("Path: " . $_SERVER['PHP_SELF']);
        header("Expires: -1");
        #header("Content-Type: UTF-8");
        header("Cache-Control: max-age=0");
    }

    public static function setContent($content) {
        header('Content-Type: text/plain');
        echo $content;
    }

    public static function putText($input) {
        if ($input == '565') {
            $main = self::menu_by_title('menu0');
            #echo "Loading App";
            echo $main->text;
            return $main->text;
        } else {
            return "BVN Content";
        }
    }

    public static function menu_by_title($title) {
        $xml = simplexml_load_file("bvn.xml");
        #var_dump($xml);
        $objects = $xml->xpath("/root/menu[title='$title']");
        #echo $obj[0]->title;
        #var_dump($objects[0]);
        return $objects[0];
    }

}

?>
