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



$uploaddir    = "foto/uploads/";
$imgupload=false;
$uploadedFile = $uploaddir . basename($_FILES["image_file"]["name"]);
if (is_uploaded_file($_FILES["image_file"]["tmp_name"])) {
    if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $uploadedFile)) {
        $data = $_FILES["image_file"];
        $imgupload=true;
    } else {
        $data['errors'] = "Во время загрузки файла произошла ошибка";
    }
} else {
    $data['errors'] = "Файл не  загружен";
}

if(!$artikul) $artikul=NULL;

$stmt = DB::run("SET NAMES utf8");
switch ($_POST['action']) {
case 'new':
    $stmt = DB::prepare('INSERT INTO claim_row VALUES (?,?,?,?,?,?,?,NULL,?,?)');
    $arg=[$uploadedFile,$artikul, $size, $probe, $vid, $gold, $num, $gemm, $comment];
    $stmt->execute($arg);
    $stmt = DB::run('SELECT LAST_INSERT_ID()')->fetch();
    $v=strval($stmt['LAST_INSERT_ID()']);
    if ($rws!='') {
        $new=$rws.','.$v;
    } else {
        $new=$v;
    }
    break;
case 'del':
    $stmt = DB::run("DELETE FROM claim_row  WHERE ind=?", [ $drow]);
    break;
case 'edit':


if($uploadedFile=='foto/uploads/')
  {
    $stmt = DB::prepare('UPDATE claim_row SET artikul=?,  size=?, probe=?, type=?, colour=?, numer=?, gemm=?, comment=? WHERE ind=?');
    $arg=[$artikul, $size, $probe, $vid, $gold, $num, $gemm, $comment,$drow];
  }
else{
  $stmt = DB::prepare('UPDATE claim_row SET image=?, artikul=?,  size=?, probe=?, type=?, colour=?, numer=?, gemm=?, comment=? WHERE ind=?');
  $arg=[$uploadedFile, $artikul, $size, $probe, $vid, $gold, $num, $gemm, $comment,$drow];
}
$stmt->execute($arg);
  break;
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
