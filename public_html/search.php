<?php
require_once 'database.php';
connect();
$num_rows = mysql_num_rows(mysql_query("SELECT * FROM `main_sup`"));
$screen_rows = 5; // количество строк на экране
$sheets = $num_rows / $screen_rows;
$search_txt = ($_POST['Search_txt']);
$current_row = ($current_sheet - 1) * $screen_rows;
$qr_result = 'select * from main_sup WHERE stl_names LIKE "%' . $search_txt . '%" ORDER BY date DESC';
$query = mysql_query($qr_result) or die("<p>Невозможно выполнить запрос: " . mysql_error() . ". Ошибка произошла в строке " . __LINE__ . "</p>");

while ($data = mysql_fetch_array($query)) {
    echo '<table style="height: 40px;" border="1" width="500" cellspacing="0" cellpadding="0">';
    echo '<tbody>';
    echo '<tr>';
    echo '<td style="width: 220px; height: 30px; background-color: #cecece; text-align: center; vertical-align: middle;">' . date("d.m.Y", strtotime($data['date'])) . '</td>';
    echo '<td style="text-align: center; vertical-align: middle;" rowspan="2"><img src="' . $data[image] . '" alt="1" width="200" height="150" /><br /><br /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td  ondblclick="PopUpShow()"; style="width: 200px; text-align: center; vertical-align: middle; background-color: #eeeeee;"><strong>' . $data[name] . '</strong> </td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
}
// echo 'В таблице '.mysql_num_rows(mysql_query("SELECT * FROM `table`")).' строк';
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="form.css" media="all">
	<!-- <link rel="stylesheet" type="text/css" href="css/view.css" media="all"> -->
	<!-- <link rel="stylesheet" type="text/css" href="css/style.css" media="all"> -->
	<!-- <link rel="stylesheet" type="text/css" href="view.css" media="all"> -->
	<!-- <script type="text/javascript" src="view.js"></script> -->
	<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/calendar.js"></script>
	<script type="text/javascript" src="js/view.js"></script>
</head>
	<script>
		function scanfolder($dir)
		{
			$files1 = scandir($dir);
		}
		function SearchStl()
		{
/*SELECT *
FROM  `main_sup`
WHERE stl_names LIKE  "%32 центр%"*/
}
		function PopUpShow(){
			$("#form_52193").show();
		}
		function PopUpHide(){
			$("#form_52193").hide();
		}
function nPage()
{
	var cp= Number(document.forms.form_812401.cp.value);
	cp++;
	var loc="/?c_page="+cp.toString();
	window.open(loc, '_self');
}
function pPage()
{
	var cp= Number(document.forms.form_812401.cp.value);
	cp--;
	if (cp==0) return;
	var loc="/?c_page="+cp.toString();
	window.open(loc, '_self');
}
</script>
<body>
<script>
		$(document).ready(function(){
		//	PopUpHide();
		}
)
</script>
</body>
