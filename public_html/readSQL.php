<?php
require_once ('database.php');

$dbase=$_GET['dbase'];
$field=$_GET['field'];
$val=$_GET['val'];




$sql="SELECT * FROM ".$dbase. " WHERE ".$field."=? ORDER BY num DESC LIMIT 1";
$stmt = DB::run('SET NAMES utf8');
$data=DB::run($sql, [$val]) -> fetch();

$res="{";
$keys = array_keys($data);
$ii=0;
foreach ($data as $val) {

$val = preg_replace('/\r\n/', ',', $val);


 $res.='"'.$keys[$ii].'": "'.$val.'",';
 $ii++;
}
$res=substr($res,0,-1);
$res.="}";

echo $res;
?>
