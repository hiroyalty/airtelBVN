<?php

class Tbvn {

    public static function getContent($input, $sessionid, $msisdn) {
        self::putModel();
        if ($input == '565') {
            $level = 0;
            //$usersess = R::dispense("mhealth");
            //$usersess->msisdn = $msisdn;
            //$usersess->sessionid = $sessionid;           
            //$usersess->level = $level;
            $amsisdn = $msisdn;
            $asessionid = $sessionid;
            $lastentry = $input;
            $operator = 'airtel';
            //$usersess->lastaction = 'menu0';
            //$id = R::store($usersess);
            $sql = db_execute("insert into bvnumber.BVN_LOGS (service_code, msisdn, operator, session_id) values ($input,$amsisdn,$operator,$asessionid)");
            if($sql)    {
            $main = self::menu_by_title('menu0');
            return $main->text;
            } else {
               echo 'error insert'; 
            }
        } else {
            //$i = R::getRow('select * from mhealth where sessionid=?', array($sessionid));
            if ($i) {
                $last_action = $i['lastaction'];
                $last_menu = self::menu_by_title($last_action);
                $next_options = explode(',', $last_menu->next);
                $next_option = $next_options[$input];
                //echo $next_option;
                //echo '<br>';
                //R::exec('update mhealth set level=level+1,lastaction=? where sessionid=?', array($next_option, $sessionid));
                $menu = self::menu_by_title($next_option);
                return $menu->text;
            } else {
                self::getContent('565', $sessionid, $msisdn);
            }
        }
    }

    public static function checkDestination($number) {
        /*
          $first = substr($number, 0, 1);
          if ($first == '+') {
          return '0' . substr($number, 4);
          } else {
          return '0' . substr($number, 3);
          }
         * 
         */
        return "0" . substr($number, 3);
    }

    public static function putModel() {
        include 'lib/oracle_dbase.php';
        //include_once 'model/rb.php';
        $dbfile = 'data-' . date('Ymd') . '.db';
        //R::setup("sqlite:data/$dbfile", 'user', 'password');
        //R::setup('mysql:host=localhost;dbname=ussd', 'ussd', 'ussd');
    }

    public static function menu_by_title($title) {
        $xml = simplexml_load_file("bvn.xml");
        #var_dump($xml);
        $objects = $xml->xpath("/root/menu[title='$title']");
        #echo $obj[0]->title;
        #var_dump($objects[0]);
        return $objects[0];
    }
    
    public static function do_response($url) {
        try {
# try pushing request to url;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPGET, 1); // Make sure GET method it used
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the result
            curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
            curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
            $res = curl_exec($ch); // Run the request
        } catch (Exception $ex) {

            $res = 'NOK';
        }
        return $res;
    }

    public static function smsSender($dnrr, $msg) {
        $id = "mhealth@vas2nets.com";
        $pw = "mHealth123$"; # ensure that you use the approved password on v2nmobile.
        $url = "http://www.v2nmobile.co.uk/api/httpsms.php?u=" . urlencode($id) . "&p=" . urlencode($pw) . "&r=" . urlencode($dnrr) . "&s=" . urlencode("776") . "&m=" . urlencode($msg) . "&t=1";
        /* invocation of URL */
        if (($f = @fopen($url, "r"))) {
            $answer = fgets($f, 255);
            //echo "<br>answer=$answer"; # remove this if you like
            return $answer;
        } else {
            return "gateway cannot be opened at the moment";
            //return false;
        }
    }

}

?>
