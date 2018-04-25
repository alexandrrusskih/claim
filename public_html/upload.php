<?php
require_once('database.php');
$print_date = $_POST['element_year'].'-'.$_POST['element_month'].'-'.$_POST['element_day'];
$print_date = date_create_from_format('Y-m-d', $print_date);

$artikul= $_POST['artikul'];
$probe= $_POST['probe'];
$gold= $_POST['gold'];
$size= $_POST['size'];
$gemm= $_POST['gemm'];
$comment= $_POST['comment'];
$vid= $_POST['vid'];
$num= 1;
$rws=$_POST['rows'];
$claim=$_POST['claim'];
$drow=$_POST['drow'];

$new=$rws;

if ($_POST['action'] !="del") {
    $stmt = DB::run("SET NAMES utf8");
    $stmt = DB::prepare('INSERT INTO claim_row VALUES (?,?,?,?,?,?,?,NULL,?,?)');
    $arg=['',$artikul, $size, $probe, $vid, $gold, $num, $gemm, $comment];
    $stmt->execute($arg);

    $stmt = DB::run('SELECT LAST_INSERT_ID()')->fetch();
    $v=strval($stmt['LAST_INSERT_ID()']);

    if ($rws!='') {
        $new=$rws.','.$v;
    } else {
        $new=$v;
    }
} else {
    // $stmt = DB::run("DELETE FROM claim_row  WHERE ind=?", [ $drow]);
}



$stmt = DB::run("UPDATE claim_table SET row=? WHERE ind=?", [$new, $claim]);
$res = '<script type="text/javascript">';
$res.= 'window.parent.handleResponse();';
$res.= '</script>';
echo $res;
?>
<html>
<head>
	<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="loader.css" media="all">
</head>
<body>

</body>
