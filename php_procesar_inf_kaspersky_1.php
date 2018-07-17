<?php 
error_reporting(E_ALL ^ E_NOTICE);

$mostrar_echo="S";					// activar los echo
function fEcho($t) {
	global $mostrar_echo;
	if ($mostrar_echo=="S") {
		echo $t;
	}
}
// --------------------- Datos de Conexion MySQL
$servername = "localhost";
$username = "admin";
$password = "lp1nd1c401";
$dbname = "lpindica01";
$sql = "";
// ----------------------------------------------- REQUIERE yum install php-mysql
// Crear connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// -----------------------------------------------
// Variables para la insercion en BD
$bd_estado = "";
$bd_servidor_virtual   = "";
$bd_grupo              = "";
$bd_dispositivo        = "";
$bd_ultima_conexion    = "";
$bd_motivo             = "";
$bd_estado_dispositivo = "";
$bd_ip                 = "";
$bd_ultima_visibilidad = "";
$bd_dominio            = "";
$bd_netbios            = "";
$bd_dominio_netbios    = "";
$bd_dominio_dns        = "";
$bd_sistema_operativo  = "";
$bd_bd_antivirus       = "";
$bd_ultimo_analisis    = "";
$bd_fecha_informe      = date('Y-m-d H:i:s');
// -----------------------------------------------



// Procesar archivo inf_kaspersky
fEcho ("INICIO"."\n");

// 1--> INICIO -- RECIBIR EL NOMBRE DE ARCHIVO A PROCESAR COMO PARAMETRO
$xmlFile = '';
foreach($argv as $value)   			// se espera un único argumento, pero se recorren todos los recibidos
{
	if ($xmlFile=='') {   			// la primera vez, se informa la variable con el argumento recibido
		if (substr($value, -4)==".xml")  {	// solo si la extension es .xml se informa la variable
			if (is_file($value) ) { 				// solo si el argumento corresponde con un archivo, se informa la variable
			$xmlFile=$value;  		
			}
		}
	}  
}
// 1--> FIN -- 

// 2--> INICIO -- PARSEAR EL XML DE TIPO EXCEL 
fEcho (">>>>>>>>>>>>>>>>>>xmlFile    :".$xmlFile."\n");

$domDoc = new DOMDocument();			// REQUIERE yum install php-xml
$domDoc->load($xmlFile);
$sheets = $domDoc->documentElement;
$item = $sheets->getElementsByTagName('Cell');
$longitud = $item->length;
$si = false;
for ($x = 0; $x <$item->length; $x++) {
	$my='';
	$my = $cells->item($x)->nodeValue;
	$texto = (String)utf8_decode ($my);
	$texto2 = "Último análisis completo"; //Para poder comparar y saber desde dónde tengo que empezar a leer los datos a insertar. 
	if (!is_null($my) && (strcmp($texto, $texto2) === 0)) {	
		fEcho ($x.">>>".$my."\n");
		// los n primeros no se procesan porque son la primera hoja de xml-excel, donde se ofrece un resumen de la protección. 
		if (strcmp($texto, $texto2) === 0){
			$i= $x +1;
			$si = true;
			}
		$z = 0;
		while (($i<=(int)$longitud) && $si && !is_null($my) ){
			$my = $item->item($i)->nodeValue;
			  if ($z==0)  {fEcho($z.">Estado                   :".$my."\n"); $bd_estado             = utf8_decode ($my);} 
				if ($z==1)  {fEcho($z.">Servidor virtual         :".$my."\n"); $bd_servidor_virtual   = utf8_decode ($my);} 
				if ($z==2)  {fEcho($z.">Grupo                    :".$my."\n"); $bd_grupo              = utf8_decode ($my);} 
				if ($z==3)  {fEcho($z.">Dispositivo              :".$my."\n"); $bd_dispositivo        = utf8_decode ($my);} 
				if ($z==4)  {fEcho($z.">Ult.conex.serv.admin     :".$my."\n"); $bd_ultima_conexion    = utf8_decode ($my);} 
				if ($z==5)  {fEcho($z.">Motivo                   :".$my."\n"); $bd_motivo             = utf8_decode ($my);} 
				if ($z==6)  {fEcho($z.">Estado dispositivo       :".$my."\n"); $bd_estado_dispositivo = utf8_decode ($my);} 
				if ($z==7)  {fEcho($z.">IP                       :".$my."\n"); $bd_ip                 = utf8_decode ($my);} 
				if ($z==8)  {fEcho($z.">Ultima visibilidad       :".$my."\n"); $bd_ultima_visibilidad = utf8_decode ($my);} 
				if ($z==9)  {fEcho($z.">Dominio windows          :".$my."\n"); $bd_dominio            = utf8_decode ($my);} 
				if ($z==10) {fEcho($z.">Nombre NetBIOS           :".$my."\n"); $bd_netbios            = utf8_decode ($my);} 
				if ($z==11) {fEcho($z.">Nombre dominio NetBIOS   :".$my."\n"); $bd_dominio_netbios    = utf8_decode ($my);} 
				if ($z==12) {fEcho($z.">Dominio DNS              :".$my."\n"); $bd_dominio_dns        = utf8_decode ($my);} 
				if ($z==13) {fEcho($z.">Sistema Operativo        :".$my."\n"); $bd_sistema_operativo  = utf8_decode ($my);} 
				if ($z==14) {fEcho($z.">BD antivirus publicada el:".$my."\n"); $bd_bd_antivirus       = utf8_decode ($my);} 
				if ($z==15) {fEcho($z.">Ultimo analisis completo :".$my."\n"); $bd_ultimo_analisis    = utf8_decode ($my);} 
				if ($z==15) {
					fEcho($i.">>>>>>>>>>>>>>>>>>>>>>>>>>>"."\n");
					// Insertar en mySql fila en tabla inf_kaspersky de prueba
					$sql = "INSERT INTO inf_kaspersky_prueba (estado, servidor_virtual, grupo, dispositivo, ultima_conexion, motivo, estado_dispositivo, ip, ultima_visibilidad, dominio, netbios, dominio_netbios, dominio_dns, sistema_operativo, bd_antivirus, ultimo_analisis, fecha_informe) VALUES ";
					$sql = $sql."('".$bd_estado."', ";
					$sql = $sql."'".$bd_servidor_virtual."', ";
					$sql = $sql."'".$bd_grupo."', ";
					$sql = $sql."'".$bd_dispositivo."', ";
					$sql = $sql."'".$bd_ultima_conexion."', ";
					$sql = $sql."'".$bd_motivo."', ";
					$sql = $sql."'".$bd_estado_dispositivo."', ";
					$sql = $sql."'".$bd_ip."', ";
					$sql = $sql."'".$bd_ultima_visibilidad."', ";
					$sql = $sql."'".$bd_dominio."', ";
					$sql = $sql."'".$bd_netbios."', ";
					$sql = $sql."'".$bd_dominio_netbios."', ";
					$sql = $sql."'".$bd_dominio_dns."', ";
					$sql = $sql."'".$bd_sistema_operativo."', ";
					$sql = $sql."'".$bd_bd_antivirus."', ";
					$sql = $sql."'".$bd_ultimo_analisis."', ";
					$sql = $sql."'".$bd_fecha_informe."' ) ";
					// -----
					fEcho($sql."\n");

					if ($conn->query($sql) === TRUE) {
						fEcho ("Insert realizada");
					} else {
						fEcho ("Error: " . $sql . "|n" . $conn->error);
					}
					// -----------------------------------------------
					$z=0;
				} else {
					$z++;
					}
					$i= $i+1;
				}
			}	
		}
// al final, cerrar la conexión mysql
$conn->close();	


// 2--> FIN --

?>