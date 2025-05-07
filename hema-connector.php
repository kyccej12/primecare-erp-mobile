<?php


ini_set("dispay_errors","On");

$host = "192.168.2.250";
$port = 5100;

// No Timeout 
set_time_limit(0);
$tstamp = date("YmdHis");

//$message = "MSH|^~\&|LIS||||$tstamp||ORM^O01|4|P|2.3.1||||||UNICODE";
//echo "Message To server :".$message ."<br/>";

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
socket_connect($socket, $host, $port) or die("Could not connect to server\n");

//socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");

//$result = socket_read ($socket, 1024) or die("Could not read server response\n");
$mydata = socket_read($socket,1024,PHP_NORMAL_READ);
echo "Reply From Server  :" . $mydata;

//$line = trim(socket_read($socket, MAXLINE));
//echo $line;
?>