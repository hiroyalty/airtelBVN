<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
#include_once 'd.php';
define('SUCCESS', 'SUCCESS');
define('FAILED', 'FAILED');
define('CANNOTINSERT', 'DUPLICATE RECORD');
define('EMPTYDATA', 'CANNOT USE EMPTY PARAMETER');
define('RETURNEMPTY', 'EMPTY RESULT SETS');
define('WRONGTOKEN', 'WRONG TOKEN OR MISSING PARAMETER');

define('DB_HOST', 'localhost');
define("ORA_USER", 'BVNUMBER');
define("ORA_PWD", 'BVNUMBER114_');
define("ORA_DB", 'xe');

function db_connect() {
    $c = oci_connect(ORA_USER, ORA_PWD, "//".DB_HOST."/" .ORA_DB);
    //$c = oci_connect(ORA_USER, ORA_PWD, "//".DB_HOST."/" . ORA_DB);
    return $c;
}

function db_query($sql, $bind = null) {
    $c = db_connect();
    $res = array();
    $s = oci_parse($c, $sql);
    if (count($bind)>1) {
        foreach ($bind as $key => $value) {
            oci_bind_by_name($s, ":".$key, $value);
        }
    }
    oci_execute($s);
    #oci_fetch_all($s, $res);
    while($row = oci_fetch_object($s)){
	//while($row = oci_fetch_all($s)){
        $res[]=$row;
    }
    return $res;
 }
     

function db_execute($sql, $bind = null) {
    $c = db_connect();
    $res = array();
    $s = oci_parse($c, $sql);
    if ($bind != null) {
        foreach ($bind as $key => $value) {
            oci_bind_by_name($s, ":".$key, htmlentities($value,ENT_QUOTES));
        }
    }
    $res = oci_execute($s);
    return $res;
}

function call_url($url) {
    try {
# try pushing request to url;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPGET, 1); // Make sure GET method it used
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the result
        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
        $res = curl_exec($ch); // Run the request
    } catch (Exception $ex) {

        $res = 'Error Calling URL';
    }
    return $res;
}

?>
