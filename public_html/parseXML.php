<?php
$art=$_POST['art'];
$jData = simplexml_load_file("NomData.xml");
$products = $jData->xpath("/XMLData/NOMENKLATURA[ART=14789]/VI");
// $products = $xml->xpath("/PRODUCTS/PRODUCT[SKU='soft5678']/NAME");
// print_r($products);
echo($products[0]);
