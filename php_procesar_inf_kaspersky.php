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
$cells = $domDoc->getElementsByTagName('Cell');
$i=0;
for ($x = 0; $x <= 1000; $x++) {
	$my='';
	$my = $cells->item($x)->nodeValue;
	if (!is_null($my) ) {	
		fEcho ($x.">>>".$my."\n");
		// los 32 primeros no se procesan
		if ($x>32) { 
				if ($i==0)  {fEcho($i.">Estado                   :".$my."\n"); $bd_estado             = utf8_decode ($my);} 
				if ($i==1)  {fEcho($i.">Servidor virtual         :".$my."\n"); $bd_servidor_virtual   = utf8_decode ($my);} 
				if ($i==2)  {fEcho($i.">Grupo                    :".$my."\n"); $bd_grupo              = utf8_decode ($my);} 
				if ($i==3)  {fEcho($i.">Dispositivo              :".$my."\n"); $bd_dispositivo        = utf8_decode ($my);} 
				if ($i==4)  {fEcho($i.">Ult.conex.serv.admin     :".$my."\n"); $bd_ultima_conexion    = utf8_decode ($my);} 
				if ($i==5)  {fEcho($i.">Motivo                   :".$my."\n"); $bd_motivo             = utf8_decode ($my);} 
				if ($i==6)  {fEcho($i.">Estado dispositivo       :".$my."\n"); $bd_estado_dispositivo = utf8_decode ($my);} 
				if ($i==7)  {fEcho($i.">IP                       :".$my."\n"); $bd_ip                 = utf8_decode ($my);} 
				if ($i==8)  {fEcho($i.">Ultima visibilidad       :".$my."\n"); $bd_ultima_visibilidad = utf8_decode ($my);} 
				if ($i==9)  {fEcho($i.">Dominio windows          :".$my."\n"); $bd_dominio            = utf8_decode ($my);} 
				if ($i==10) {fEcho($i.">Nombre NetBIOS           :".$my."\n"); $bd_netbios            = utf8_decode ($my);} 
				if ($i==11) {fEcho($i.">Nombre dominio NetBIOS   :".$my."\n"); $bd_dominio_netbios    = utf8_decode ($my);} 
				if ($i==12) {fEcho($i.">Dominio DNS              :".$my."\n"); $bd_dominio_dns        = utf8_decode ($my);} 
				if ($i==13) {fEcho($i.">Sistema Operativo        :".$my."\n"); $bd_sistema_operativo  = utf8_decode ($my);} 
				if ($i==14) {fEcho($i.">BD antivirus publicada el:".$my."\n"); $bd_bd_antivirus       = utf8_decode ($my);} 
				if ($i==15) {fEcho($i.">Ultimo analisis completo :".$my."\n"); $bd_ultimo_analisis    = utf8_decode ($my);} 
				if ($i==15) {
					fEcho($i.">>>>>>>>>>>>>>>>>>>>>>>>>>>"."\n");
					// Insertar en mySql fila en tabla inf_kaspersky
					$sql = "INSERT INTO inf_kaspersky (estado, servidor_virtual, grupo, dispositivo, ultima_conexion, motivo, estado_dispositivo, ip, ultima_visibilidad, dominio, netbios, dominio_netbios, dominio_dns, sistema_operativo, bd_antivirus, ultimo_analisis, fecha_informe) VALUES ";
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
					$i=0;
				} else {
					$i++;
					}
			}	
		}
	}
	
// al final, cerrar la conexión mysql
$conn->close();	


// 2--> FIN --

?>