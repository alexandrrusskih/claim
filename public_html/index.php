<?php
require_once 'database.php';
if (!isset($_GET['c_page'])) {
    $_GET['c_page'] = 1;
}
// $row = DB::run("SET NAMES utf8");
$cl=$_GET['c_page'];
$rows= array();

$sql = "SELECT * FROM `claim_table` ORDER BY ind DESC ";
$title = DB::run($sql);
$rws=$row['row'];
$claim_n=$row['num'];
$date= date("d.m.Y", strtotime($row['date']));
$rows = explode(",", $rws);
$count = count($rows);
$ind = count($ind);

echo '<form id="form_812401" " class="form-container" enctype="multipart/form-data" method="post" action="search.php"  />';
echo '<input name="add" class="submit-button" type="button" value="Новая заявка"  onclick="new_claim()" />';
echo '<input id="cp" name="c_page" value="' . $current_sheet . '" type="HIDDEN" />';
echo '</form>';
echo '<table class="claim_table"  border="1"  cellspacing="0" cellpadding="0" >';
echo '<tbody>';

while ($row = $title->fetch(PDO::FETCH_LAZY)) {
    $num=$row['num'];
    $ind=$row['ind'];
    $comment=$row['comment'];
    $date= date("d.m.Y", strtotime($row['date']));
    echo '<tr>';
    echo '<td onclick="open_claim(this)"; style="width:280px";"><strong> Заявка №' . $num . '</strong>  </td>';
    echo '<td ondblclick="edit_claim(this)" style=" width:200px; padding:10;  ">' . $date. ' </td>';
    echo '<td  style=" width:200px; padding:10;  ">' . $comment. ' </td>';
    echo '<td style="visibility: hidden; border:none;">' . $ind. ' </td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="form.css" media="all">
	<link rel="stylesheet" type="text/css" href="loader.css" media="all">
	<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/calendar.js"></script>
	<script type="text/javascript" src="js/view.js"></script>
	<script type="text/javascript" src="js/search.js"></script>
</head>
<script>

 function edit_claim(row) {
   // return;
   var $row = $(row);
   var $rw = $row.parents('tbody tr');
   var ind = $rw.children('td');
   var _date = $(ind[1]).text().trim().split(".");
   var _claim = $(ind[3]).text().trim();
   var _num = $(ind[0]).text().trim().split("Заявка №");

   $('#form_new_claim :input[name="claim_num"]').val(_num[1]);
   $('#form_new_claim :input[name="element_day"]').val(_date[0]);
   $('#form_new_claim :input[name="element_month"]').val(_date[1]);
   $('#form_new_claim :input[name="element_year"]').val(_date[2]);
   $('#form_new_claim :input[name="action"]').val("edit");
   $('#form_new_claim :input[name="claim"]').val(_claim);
   $('#form_new_claim :input[name="submit"]').val("Сохранить");
   PopUpShow();
 }

  function open_claim(row) {
    $('#loader').css("visibility","visible");
    var $row = $(row);
    var $rw = $row.parents('tbody tr');
    var ind = $rw.children('td');
    var num = $(ind[3]).text().trim();
    var loc = "claim.php?c_page=" + num.toString();
    window.open(loc, '_self');
  }

  function ShowLoader(){
     $('#loader').css("visibility","visible");
  PopUpHide();

  }

  	function PopUpShow() {
  	  // $("#form_52193").show();
  $('#form_new_claim').css("visibility","visible");

  	}

  	function PopUpHide() {
  	  // $("#form_52193").hide();
      $('#form_new_claim').css("visibility","hidden");
  	}




	function handleResponse(mes) {

num=mes.indx;
// console.log(num);
    var loc = "claim.php?c_page=" + num.toString();
    window.open(loc, '_self');
    // window.location.reload();
	}

  function new_claim(){
    $('#form_new_claim :input[name="claim_num"]').val("");
    $('#form_new_claim :input[name="submit"]').val("Добавить");
    $('#form_new_claim :input[name="action"]').val("new");
    PopUpShow();
  }

  function add_claim(){
    ShowLoader();
    PopUpHide();
//    var num=  $('#form_new_claim :input[name="claim_num"]').text().trim();

  //  num=mes.indx;
  //  console.log(num);
  //      var loc = "claim.php?c_page=" + num.toString();
    //    window.open(loc, '_self');
  }


</script>
<body>
	<script>
	  $(document).ready(function() {
	    PopUpHide();
	    var currentDate = new Date(),
	      currentDay = currentDate.getDate() < 10 ? '0' + currentDate.getDate() :
	      currentDate.getDate(),
	      currentMonth = currentDate.getMonth() < 9 ? '0' + (currentDate.getMonth() + 1) :
	      (currentDate.getMonth() + 1);
	    $('#form_new_claim :input[name="element_day"]').val(currentDay);
	    $('#form_new_claim :input[name="element_month"]').val(currentMonth);
	    $('#form_new_claim :input[name="element_year"]').val(currentDate.getFullYear());
	  })
    </script>

  <form id="form_new_claim" class="fcontainer" style="width:300px;" enctype="multipart/form-data" visible="false" method="post" target="hiddenframe"   action="new_claim.php" onsubmit="add_claim()">
  <ul>
    <li id="li_2">
      <label class="description" for="claim_num">Номер заявки </label>
      <div>
        <input id="claim_num" name="claim_num" class="element text medium" type="text" maxlength="155" value="" />
      </div>
    </li>
    <li id="li_3"> <!--  Calendar !-->
      <label class="description" for="element_2">Дата</label>
      				<span>
      					<input id="element_2_2" name="element_day" class="element text" size="2" maxlength="2" value="" type="text"/>
      					<label for="element_2_2">DD</label>
      				</span>
      				<span>
      					<input id="element_2_1" name="element_month" class="element text" size="2" maxlength="2" value="" type="text"/>
      					<label for="element_2_1">MM</label>
      				</span>
      				<span>
      					<input id="element_2_3" name="element_year" class="element text" size="4" maxlength="4" value="" type="text"/>
      					<label for="element_2_3">YYYY</label>
      				</span>
      				<span id="calendar_2">
      					<img id="cal_img_2" class="datepicker" src="calendar.gif" alt="Pick a date.">
      				</span>
      				<script type="text/javascript">
      					Calendar.setup({
      						inputField	 : "element_2_3",
      						baseField    : "element_2",
      						displayArea  : "calendar_2",
      						button		 : "cal_img_2",
      						ifFormat	 : "%B %e, %Y",
      						onSelect	 : selectDate
      					});
      				</script>
    </li>

    <li id="li_4">
      <label class="description" for="comment">Комментарий</label>
      <div>
        <input id="comment" name="comment" class="element text medium" type="text" maxlength="155" value=""  disabled>
      </div>
    </li>

      <li class="buttons">
      <input type="hidden" name="date" value="" />
      <input type="hidden" name="claim" value="" />
      <input type="hidden" name="action" value="" />
      <input type="hidden" name="form_id" value="form_new_claim" />
      <input id="saveForm" class="submit-button" type="submit" name="submit" value="Добавить" />
      <input onclick="PopUpHide()" class="submit-button" type="button" name="cancel" value="Отмена" />
    </li>
  </ul>
</form>

	<iframe id="hiddenframe" name="hiddenframe" style="width:0px; height:0px; border:0px"></iframe>

  <div class="cssload-thecube" id="loader">
    <div class="cssload-cube cssload-c1"></div>
    <div class="cssload-cube cssload-c2"></div>
    <div class="cssload-cube cssload-c4"></div>
    <div class="cssload-cube cssload-c3"></div>
  </div>


</body>
