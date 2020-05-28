<?php
require_once('database.php');
$print_date = $_POST['element_year'].'-'.$_POST['element_month'].'-'.$_POST['element_day'];
$claim_num= $_POST['claim_num'];
if ($claim_num=='') {
}
$ind=$_POST['claim'];

$act=$_POST['action'];

$stmt = DB::run("SET NAMES utf8");

if ($act=="edit") {
    $arg=[$print_date, $claim_num,$ind];
    $stmt = DB::prepare('UPDATE claim_table SET date=?,  num=? WHERE ind=?');
} else {
    $stmt = DB::prepare('INSERT INTO claim_table VALUES (?,?,?,NULL,NULL, NULL)');
    $arg=['',$print_date, $claim_num];
}
$stmt->execute($arg);

$stmt = DB::run('SELECT LAST_INSERT_ID()')->fetch();
$v=strval($stmt['LAST_INSERT_ID()']);

$res = '<script type="text/javascript">';
$res.= 'var data = new Object;';
$res.='data.indx="'.$v.'";';

$res.= 'window.parent.handleResponse(data);';
$res.= '</script>';
echo $res;
?>
<html>
<head>
	<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
</head>
<body>
</body>
