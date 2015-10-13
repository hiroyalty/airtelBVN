<?php
include_once 'bootstrap.php';

if (isset($_REQUEST['clean']) && $_REQUEST['clean'] == 'clean-session') {
    App::logRequesToFile(App::requestToArray());
    App::setCleanUpHeaders();
} else {
    $headers = array("Freeflow" => "FC", "Charge" => "Y", "Amount" => "10");
    App::setNormalHeaders($headers);
    $content = App::getResponse();
    App::setContent($content);
}
?>
  