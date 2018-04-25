<?php
require_once 'database.php';
if (!isset($_GET['c_page'])) {
    $_GET['c_page'] = 1;
}
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf8">
  <link rel="stylesheet" type="text/css" href="form.css" media="all">
  <link rel="stylesheet" type="text/css" href="loader.css" media="all">
  <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="js/calendar.js"></script>
  <script type="text/javascript" src="js/view.js"></script>
  <script type="text/javascript" src="js/search.js"></script>
</head>

<?php
$cl=$_GET['c_page'];
$rows= array();

$row = DB::run("SET NAMES utf8");
$row = DB::run("SELECT * FROM claim_table WHERE ind=?", [$cl])->fetch();
$rws=$row['row'];
$claim_n=$row['num'];
$date= date("d.m.Y", strtotime($row['date']));
$rows = explode(",", $rws);
$count = count($rows);
$ind = count($ind);

echo '<form id="form_812401" class="form-container" enctype="multipart/form-data" method="post" />';
echo 'Заявка №';
echo $claim_n;
echo '&nbsp; &nbsp';
echo 'от';
echo '&nbsp; &nbsp';
echo $date;
echo '<br>';
echo '<hr>';
echo '<br>';
echo '<input name="add" class="submit-button" type="button" value="Добавить изделие"  onclick="new_row()" />';
 echo '<input name="save_claim"  class="submit-button" type="button" value="Закрыть заявку"  onclick="Close_claim()" />';
echo '<input id="cp" name="c_page" value="' . $current_sheet . '" type="HIDDEN" />';
echo '</form>';

if ($rws!='') {
    echo '<table  border="1"  cellspacing="0" cellpadding="0" >';
    echo '<tbody>';

    echo '  <tr>';
    echo '<th>&nbsp;</th><th>Артикул</th><th>Размер</th><th>Проба</th><th>Вставка</th><th>Примечание</th>';
    echo '</tr>';

    for ($i = 0; $i < $count; $i++) {
        $row = DB::run("SELECT * FROM claim_row WHERE ind=?", [$rows[$i]])->fetch();
        $art = $row['artikul'];
        $type = $row['type'];
        $color = $row['colour'];
        $probe = $row['probe'];
        $size = $row['size'];
        $gemm = $row['gemm'];
        $num=$row['numer'];
        $ind=$row['ind'];
        $comment = $row['comment'];
        $imga = 'foto/' . $art . '.jpg';
        echo '<tr  ondblclick="deleteRow(this)">';
        echo '<td ><img src="' . $imga . '" /></td>';
        echo '<td style="width:200px";"><strong>' . $art . '</strong></br><hr>' . $type . ' </td>';
        if ($size) {
            echo '<td style= padding:10px "><strong>' . $size . '<strong> </td>';
        } else {
            echo '<td > </td>';
        }
        echo '<td style ="padding:10">' . $probe . '<hr>' . $color . '    </td>';
        echo '<td style="width:200px;  padding:10; ">' . $gemm . ' </td>';
        echo '<td style="width:200px;  padding:10; ">' . $comment . ' </td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
?>



<script>
	function scanfolder($dir) {
	  $files1 = scandir($dir);
	}


function deleteRow(row) {
  var rIndex = row.rowIndex;

 $('#deleteRow').css("visibility","visible");
 $('#deleteRow :input[name="action"]').val("del");
 var rws = "<?php echo $rws ?>";
 var ind = <?php echo $cl ?>;
var pat=/\d+/gi;
// console.log(rws);

var newRws='';
var myArray = rws.match(pat);
var n=1;
for (var i = 0; i < myArray.length; i++) {
if (i+1!=rIndex)newRws=newRws+myArray[i]+",";
}
newRws=newRws.slice(0,-1);
 // var drow=myArray[(rIndex-1)];
 // console.log(newRws);

 $('#deleteRow :input[name="drow"]').val(myArray[(rIndex-1)]);
 $('#deleteRow :input[name="rows"]').val(newRws);
 $('#deleteRow :input[name="claim"]').val(ind);


}


function edit_row(row){
$row=$(row) ;
var cells = $row.children();
var txt=$(cells[1]).text().trim();
var pat=/\d+/;
var myArray = pat.exec(txt);
$('#form_52193 :input[name="artikul"]').val(myArray[0]);
 pat=/\D+/;
 myArray = pat.exec(txt);
$('#form_52193 :input[name="vid"]').val(myArray[0]);
txt=$(cells[2]).text().trim();  //-size
$('#form_52193 :input[name="size"]').val(txt);
txt=$(cells[3]).text().trim();
pat=/\d+/;
myArray = pat.exec(txt);
$('#form_52193 :input[name="probe"]').val(myArray[0]);
pat=/\D+/;
myArray = pat.exec(txt);
$('#form_52193 :input[name="gold"]').val(myArray[0]);
txt=$(cells[4]).text().trim();  //font-size
$('#form_52193 :input[name="gemm"]').val(txt);
txt=$(cells[5]).text().trim();  //font-size
$('#form_52193 :input[name="comment"]').val(txt);
PopUpShow();
}


function ShowLoader(){
   $('#loader').css("visibility","visible");
PopUpHide();

}

	function PopUpShow() {
	  // $("#form_52193").show();
$('#form_52193').css("visibility","visible");

	}

	function PopUpHide() {
	  // $("#form_52193").hide();
    $('#form_52193').css("visibility","hidden");
    $('#deleteRow').css("visibility","hidden");
	}

	function handleResponse(mes) {
	  window.location.reload();
	}

	function new_row() {
	  var rws = "<?php echo $rws ?>";
	  var ind = <?php echo $cl ?>;
	  $('#form_52193 :input[name="rows"]').val(rws);
	  $('#form_52193 :input[name="claim"]').val(ind);
	  PopUpShow();
	}

function  Close_claim(){
  var loc = "index.php";
  window.open(loc, '_self');
  }

</script>

<body>
	<script>
    $(window).load(function() {
      $("html, body").animate({ scrollTop: $(document).height() }, 1000);
      PopUpHide();
    });
	</script>

  <form id="deleteRow" class="fcontainer" style="width:auto; top:350px; left:50%;" enctype="multipart/form-data" visible="false" method="post" target="hiddenframe" action="upload.php" onsubmit="ShowLoader()">
  <ul>
    <li id="li_2">
      <label class="description" for="artikul">Удалить строку? </label>
    </li>
    <li class="buttons">
      <input type="hidden" name="rows" value="" />
      <input type="hidden" name="claim" value="" />
      <input type="hidden" name="action" value="" />
      <input type="hidden" name="drow" value="" />
      <input type="hidden" name="form_id" value="52193" />
      <input id="saveForm" class="submit-button" type="submit" name="submit" value="Да" />
      <input onclick="PopUpHide()" class="submit-button" type="button" name="submit" value="Нет" />
    </li>
  </ul>
</form>

	<form id="form_52193" class="fcontainer" enctype="multipart/form-data" visible="false" method="post"  action="upload.php" target="hiddenframe" onsubmit="ShowLoader()">
  <ul>
    <li id="li_2">
      <label class="description" for="artikul">Артикул </label>
      <div>
        <input id="artikul" name="artikul" class="element text medium" type="text" maxlength="255" value="" />
      </div>
    </li>
    <li id="li_3">
      <label class="description" for="gold">Золото </label>
      <div>
        <select class="element select medium" id="gold" name="gold">
            <option value="" selected="selected"></option>
          <option value="Белое" >Белое</option>
          <option value="Желтое" >Желтое</option>
          <option value="БЗ+ЖЗ" >БЗ+ЖЗ</option>
        </select>
      </div>
    </li>
    <li id="li_5">
      <label class="description" for="vid">Вид изделия </label>
      <div>
        <select class="element select medium" id="vid" name="vid">
						<option value="" selected="selected"></option>
						<option value="Кольцо" >Кольцо</option>
						<option value="Серьги" >Серьги</option>
						<option value="Брошь" >Брошь</option>
						<option value="Подвеска" >Подвеска</option>
						<option value="Браслет" >Браслет</option>
						<option value="Запонки" >Запонки</option>
					</select>
      </div>
    </li>
    <li id="li_7">
      <label class="description" for="probe">Проба </label>
      <div>
        <select class="element select medium" id="probe" name="probe">
						<option value="" selected="selected"></option>
						<option value="750" >750</option>
						<option value="585" >585</option>
					</select>
      </div>
    </li>

    <li id="li_4">
      <label class="description" for="size">Размер</label>
      <div>
        <input id="size" name="size" class="element text medium" type="text" maxlength="255" value="" />
      </div>
    </li>
    <li id="li_8">
      <label class="description" for="gemm"> Вставка </label>
      <div>
        <input id="gemm" name="gemm" class="element text medium" type="text" maxlength="255" value="" />
      </div>
    </li>
    <li id="li_6">
      <label class="description" for="comment"> Комментарий </label>
      <div>
        <input id="comment" name="comment" class="element text medium" type="text" maxlength="255" value="" />
      </div>
    </li>

    <li class="buttons">
      <input type="hidden" name="rows" value="" />
      <input type="hidden" name="claim" value="" />
      <input type="hidden" name="form_id" value="52193" />
      <input id="saveForm" class="submit-button" type="submit" name="submit" value="Добавить" />
      <input onclick="PopUpHide()" class="submit-button" type="button" name="submit" value="Отмена" />
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
</html>
