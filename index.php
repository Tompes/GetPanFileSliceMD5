<?php
/**
 * Author Tompes
 * Github https://github.com/Tompes/GetPanFileSliceMD5
 */

require 'func.class.php';
header("Content-type: application/json; charset=utf-8");
$s = new GetSliceMD5(); //instantiate

if(isset($_GET['action'])){
    $act = addslashes($_GET['action']);
    if($act=="smd5"){
        if(empty($_GET['link']))
            die("{\"error\":\"-1\",\"msg\":\"parameter error.\"}");

        $link = addslashes($_GET['link']);
        $hash = $s->sliceMD5(urldecode($link));
        if(!$hash)
            die("{\"error\":\"-2\",\"msg\":\"{$s->message}\"}");

        echo "{\"error\":\"0\",\"md5\":\"{$hash}\"}";
    }
}
