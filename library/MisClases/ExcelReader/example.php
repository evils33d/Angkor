<?php

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>PHPExcel Reader Example #01</title>

</head>
<body>

<h1>PHPExcel Reader Example #01</h1>
<?php

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . './Classes/');

/** PHPExcel_IOFactory */
include 'PHPExcel/IOFactory.php';


$inputFileName = '/cartolas/cartola.xls';
echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


echo '<hr />';

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
$cabecera="<table>";
$linea = "";

foreach ($sheetData as $key => $data){
	
	if($key >= 23){
		
			$valor = "";
			foreach ($data as $key1 => $celda){

				if($key == 23){
						$valor .= "<td>".$celda."</td>";
				}				
				if($key > 23){
					
					$valor .= "<td>".$celda."</td>";
				}
			}
			
			
		
			$linea .= "<tr>".$valor."</tr>";

	}
	
}
$cabecera .= $linea;
$cabecera .= "</table>";
echo $cabecera;
//var_dump($sheetData);


?>
<body>
</html>