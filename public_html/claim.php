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

$artikul_array=DB::run('SELECT DISTINCT artikul, probe,  e_parts, w_parts, y_parts, r_parts FROM master_items ');




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
        $imga=$row['image'];
        $art = $row['artikul'];
        $type = $row['type'];
        $color = $row['colour'];
        $probe = $row['probe'];
        $size = $row['size'];
        $gemm = $row['gemm'];
        $num=$row['numer'];
        $ind=$row['ind'];
        $comment = $row['comment'];

        $row1 = DB::run("SELECT * FROM master_items WHERE artikul=?", [$art])->fetch();
        $locker=$row1['exclusive_locker'];

        if ($imga=="foto/uploads/" || $imga=="") {
            $imga = 'foto/' . $art . '.jpg';
        }

        echo '<tr  ondblclick="edit_row(this)">';
        echo '<td ><img src="' . $imga . '" /></td>';
        echo '<td style="width:200px";"><strong>' . $art . '</strong></br><hr>' . $type . ' </td>';
        if ($size) {
            echo '<td style= padding:10px "><strong>' . $size . '<strong> </td>';
        }
        else if ($locker) {
            echo '<td style= padding:10px "><strong>' . $locker . '<strong> </td>';
        }


        else {
            echo '<td > </td>';
        }
        echo '<td style ="padding:10">' . $probe . '<hr>' . $color . '    </td>';
        echo '<td style="width:200px;  padding:10; ">' . $gemm . ' </td>';
        echo '<td style=" color: #ff0000; width:200px;  padding:10; "><strong>' . $comment . '</strong> </td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
?>

<script>
var xmlhttp1;


function getArtikul(e){
console.log(e.value);
$.ajax({
    url: "readSQL.php",
    type: "GET",
    data: "dbase=master_items&field=artikul&val=" + e.value,
    success: function(html) {
        if (html) {
            var obj = JSON.parse(html);
            var t = new Object;
            console.log(obj);

	           $('#form_52193 :input[name="probe"]').val(obj['probe']);
	           $('#form_52193 :input[name="vid"]').val(obj['jew_type']);
              var g=0;
              var gl='';

             if (obj['r_parts']!='0')g+=1;
             if (obj['y_parts']!='0')g+=2;
             if (obj['w_parts']!='0')g+=4;
             if (obj['e_parts']!='0')g+=8;

             if (g==1) gl="Красное";
             if (g==2) gl="Желтое";
             if (g==4) gl="Белое";
             if (g==8) gl="Евро";
             if (g==6) gl="БЗ+ЖЗ";

             $('#form_52193 :input[name="gold"]').val(gl);


            //      slist.value = resp;
        }
    }
});




}

$(document).ready(function() {
  $("#form_52193").keydown(function(event){
    if(event.keyCode == 13) {
       event.preventDefault();
       return false;
      }
   });
})

	function scanfolder($dir) {
	  $files1 = scandir($dir);
	}

function deleteRow(row) {
	var rIndex = $('#deleteRow :input[name="rows"]').val();
	var rws = "<?php echo $rws ?>";
	var ind = <?php echo $cl ?>;
	var pat = /\d+/gi;
	var drow = $('#form_52193 :input[name="drow"]').val();
	var newRws = '';
	var myArray = rws.match(pat);
	var n = 1;
	for(var i = 0; i < myArray.length; i++) {
		if(i != rIndex) newRws = newRws + myArray[i] + ",";
	}
	newRws = newRws.slice(0, -1);
	$('#form_52193 :input[name="drow"]').val(drow);
	$('#form_52193 :input[name="rows"]').val(newRws);
	$('#form_52193 :input[name="claim"]').val(ind);
	$('#deleteRow').css("visibility", "visible");
	$('#form_52193 :input[name="action"]').val("del");
}

function getData() {
	var art = $('#form_52193 :input[name="artikul"]').val();
	if(window.XMLHttpRequest) xmlhttp1 = new XMLHttpRequest();
	else
	if(window.ActiveXObject) xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
	else alert("Извините! Ваш браузер не поддерживает XMLHTTP!");
	var url = "parseXML.php?art=" + art;
	xmlhttp1.open("GET", url, true);
	xmlhttp1.send();
	xmlhttp1.onreadystatechange = getXML;
}

function getXML() {
  	if (xmlhttp1.readyState == 4) {
  		if (xmlhttp1.responseText) {
  			var resp = xmlhttp1.responseText.replace("\r\n", "");
  			console.log(resp);
  		}
  	}
  }


function edit_row(row) {
	var rIndex = row.rowIndex;
	$row = $(row);
	var cells = $row.children();
	var txt = $(cells[1]).text().trim();

	var pat = /\d+/;
	var myArray = pat.exec(txt);
	$('#form_52193 :input[name="artikul"]').val(myArray[0]);
	pat = /\D+/;
	myArray = pat.exec(txt);
	$('#form_52193 :input[name="vid"]').val(myArray[0]);
	txt = $(cells[2]).text().trim(); //-size
	$('#form_52193 :input[name="size"]').val(txt);
	txt = $(cells[3]).text().trim();
	pat = /\d+/;
	myArray = pat.exec(txt);
	$('#form_52193 :input[name="probe"]').val(myArray[0]);
	pat = /\D+/;
	myArray = pat.exec(txt);
  console.log({txt});
if(myArray)  $('#form_52193 :input[name="gold"]').val(myArray[0]);
	txt = $(cells[4]).text().trim(); //font-size
	$('#form_52193 :input[name="gemm"]').val(txt);
	txt = $(cells[5]).text().trim(); //font-size
	$('#form_52193 :input[name="comment"]').val(txt);
	$('#form_52193 :input[name="action"]').val("edit");
	var rws = "<?php echo $rws ?>";
	var pat = /\d+/gi;
	var myArray = rws.match(pat);
	$('#form_52193 :input[name="drow"]').val(myArray[(rIndex - 1)]);
	$('#deleteRow :input[name="rows"]').val(rIndex - 1);
	$('#form_52193 :input[name="submit"]').val("Сохранить");
	$('#form_52193 :input[name="delete"]').show();
	PopUpShow();
}


function ShowLoader() {
	$('#loader').css("visibility", "visible");
	PopUpHide();
}

	function PopUpShow() {
    $('#form_52193').css("visibility","visible");
	}

	function PopUpHide() {
    $('#form_52193').css("visibility","hidden");
    $('#deleteRow').css("visibility","hidden");
	}

	function handleResponse(mes) {
	  window.location.reload();
	}

	function new_row() {
	  var rws = "<?php echo $rws ?>";
	  var ind = <?php echo $cl ?>;
    document.getElementById("form_52193").reset();
    $('#form_52193 :input[name="rows"]').val(rws);
	  $('#form_52193 :input[name="claim"]').val(ind);
	  $('#form_52193 :input[name="action"]').val("new");
    $('#form_52193 :input[name="submit"]').val("Добавить");
    $('#form_52193 :input[name="delete"]').hide();
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

  <form id="deleteRow" class="fcontainer" style="z-index: 900; width:auto; top:350px; left:50%;" enctype="multipart/form-data" visible="false" method="post" target="hiddenframe" action="upload.php" onsubmit="ShowLoader()">
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

	<form id="form_52193" class="fcontainer" enctype="multipart/form-data" visible="false" method="post"  action="upload.php"  target="hiddenframe" onsubmit="ShowLoader()">
  <ul>
    <li id="li_2">
      <label class="description" for="artikul">Артикул </label>
      <div>
        <!-- <input id="artikul" name="artikul" onchange="getData()" class="element text medium" type="text" maxlength="255" value="" /> -->
<span>
        <input list="arts"  id="artikul" name="artikul"  class="element text medium" maxlength="255" value="" autocomplete="on" onchange="getArtikul(this)">
          <datalist id='arts'>
<?php

while ($data = $artikul_array->fetch(PDO::FETCH_ASSOC)) {

echo '<option value="'.$data['artikul'].'">';

}

?>


          </datalist>

</span>
<span>
        <input id="element_1" name="image_file" class="element file" type="file" accept="image/jpeg,image/png,image/gif"  >
      </span>
      </div>
    </li>
    <li id="li_3">
      <label class="description" for="gold">Золото </label>
      <div>
        <select class="element select medium" id="gold" name="gold">
            <option value="" selected="selected"></option>
          <option value="Красное" >Красное</option>
          <option value="Белое" >Белое</option>
          <option value="Желтое" >Желтое</option>
          <option value="БЗ+ЖЗ" >БЗ+ЖЗ</option>
          <option value="Евро" >Евро</option>
          <option value="ЕЗ+КЗ+БЗ" >ЕЗ+КЗ+БЗ</option>
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
						<option value="Брошь-подвеска" >Брошь-подвеска</option>
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
      <input type="hidden" name="drow" value="" />
      <input type="hidden" name="claim" value="" />
      <input type="hidden" name="action" value="" />
      <input type="hidden" name="form_id" value="52193" />
      <input id="saveForm" class="submit-button" type="submit" name="submit" value="Добавить" />
      <input onclick="PopUpHide()" class="submit-button" type="button" name="cancel" value="Отмена" />
      <input onclick="deleteRow()" class="submit-button" type="submit" name="delete" value="Удалить" />
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
