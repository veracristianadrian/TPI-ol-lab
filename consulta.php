<?php

header('Content-type: text/html; charset=utf-8;');
$wkt =  $_GET['wkt'];

$link= pg_connect("host=localhost user=user password=user dbname=qgisserver");

$query=<<<EOD
SELECT * FROM red_vial WHERE 
st_intersects(
ST_geomfromtext('$wkt',4326),
geom
)

EOD;


echo $query;
$result = pg_query($link,$query);

$nro_campos = pg_num_fields($result);
$nro_registros = pg_num_rows($result);

$header = '<tr>';
$i = 0;
while ($i < $nro_campos) { 
	$fieldName = pg_field_name($result, $i); 
	
	if($fieldName!='geom'){
		$header.= '<td>' . $fieldName .'</td>'; 
	}
	$i++; 
	
}
$header .= '</tr>';

$cuerpo='';
while ($row = pg_fetch_row($result)) { 
	$cuerpo.= '<tr>'; 
	$i=0;
	while ($i < $nro_campos) { 
		 if(pg_field_name($result, $i)!='geom'){
			 $cuerpo.= '<td>' . $row[$i] . '</td>';
		}
		$i++;
	} 
	$cuerpo.= '</tr>'; 
	
}



?>
<!doctype html>
<html lang="en">
		<style>
			body, table{
				font-family: Arial, Helvetica, sans-serif;
				font-size: 11px;			
			}
		</style>
	</head>
<body>

<h3>Nro. Registros: <?php echo $nro_registros;?></h3>
<table border=1 cellpading=0 cellspacing=0>
<?php echo $header ?>
<?php echo $cuerpo ?>
</table>
</body>