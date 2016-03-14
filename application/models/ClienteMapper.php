<?php

class Application_Model_ClienteMapper
{
	protected $_dbTable;
	
	protected $_host;
	protected $_username;
	protected $_password;
	protected $_dbname;
	protected $_link;
	
	public function __construct() 
	{
		$bootstrap = Zend_Controller_Front::getInstance ()->getParam ( 'bootstrap' );
		$options = $bootstrap->getOptions ();
		
		$this->_host = $options ['resources'] ['db'] ['params'] ['host'];
		$this->_username = $options ['resources'] ['db'] ['params'] ['username'];
		$this->_password = $options ['resources'] ['db'] ['params'] ['password'];
		$this->_dbname = $options ['resources'] ['db'] ['params'] ['dbname'];
		
		//$this->_link = mysql_connect ( $host, $username, $password ) or die ( mysql_error () );
		$this->_link = mysqli_connect($this->_host,$this->_username,$this->_password, $this->_dbname) or die ( mysqli_error($this->_link));
		//mysql_select_db ( $dbname, $link ) or die ( mysql_error () );
		
		$sql1 = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
		
		mysql_query($sql1);
	}
        
	public function ingresarCliente($data)
	{
		$sql = "INSERT INTO `ci03_cliente` (
				  `lc01_idUsuario`, 
				  `ci03_nombre`, 
				  `ci03_fechaingreso`, 
				  `ci03_estadodisponibilidad`,
				  `ci03_fechanacimiento`, 
				  `ci03_sexo`, 
				  `ci03_especialidad`,  
				  `ci03_trabajouno`,
				  `ci03_trabajodos`,
				  `ci03_saldo`
				)
				VALUES
				('" . $data ['idEjecutivo'] . "',
				 '" . $data ['nombreCliente'] . "',
				 STR_TO_DATE('".$data ['fechaIngreso']."','%d-%m-%Y'),
				 '1',
				 '',
				 '',
				 '',
				 '',
				 '',
				 '0');";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public function listadoClientes() {
	
		$sql = "SELECT 
				  c.`ci03_idcliente`,
				  c.`ci03_nombre`,
				  u.`lc01_nombreUsuario` 
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `lc01_usuario` u 
				    ON c.`lc01_idusuario` = u.`lc01_idUsuario` 
				WHERE c.`ci03_estadodisponibilidad` = '1';";
	
		$listadoClientes = mysql_query ( $sql );
	
		$clientes = array ();
	
		while ( $row = mysql_fetch_array ( $listadoClientes ) ) {
			$entry ['ci03_idcliente'] = $row ['ci03_idcliente'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
				
			$clientes [] = $entry;
		}
	
		return $clientes;
	}
	
	public function listadoClientesByEjecutivo($idUsuario) {
	
		$sql = "SELECT 
				  c.`ci03_idcliente`,
				  c.`ci03_nombre`,
				  u.`lc01_nombreUsuario` 
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `lc01_usuario` u 
				    ON c.`lc01_idusuario` = u.`lc01_idUsuario` 
				WHERE u.`lc01_idUsuario` = '".$idUsuario."' 
				  AND c.`ci03_estadodisponibilidad` = '1';";
	
		$listadoClientes = mysql_query ( $sql );
	
		$clientes = array ();
	
		while ( $row = mysql_fetch_array ( $listadoClientes ) ) {
			$entry ['ci03_idcliente'] = $row ['ci03_idcliente'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
	
			$clientes [] = $entry;
		}
	
		return $clientes;
	}

	public function listarByNombre($nombre,$idEjecutivo)
	{
		
		$filtro="";
		
		if($idEjecutivo!='')
		{
			$filtro.="AND u.`lc01_idUsuario`='".$idEjecutivo."'";
		}
		
		if($nombre!='')
		{
			$filtro.="AND c.`ci03_nombre` LIKE '".$nombre."%'";
		}
		
		
		$sql = "SELECT 
			      c.`ci03_idcliente`,
				  c.`ci03_nombre`,
				  u.`lc01_nombreUsuario`
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `lc01_usuario` u 
				    ON c.`lc01_idusuario` = u.`lc01_idUsuario` 
				WHERE c.`ci03_estadodisponibilidad` = '1' $filtro ;";
		
		$listadoClientes = mysql_query ( $sql );
		
		$clientes = array ();
		
		while ( $row = mysql_fetch_array ( $listadoClientes ) ) {
			$entry ['ci03_idcliente'] = $row ['ci03_idcliente'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
		
			$clientes [] = $entry;
		}
		
		return $clientes;
	}
	
	public function datosClienteById($id)
	{
		$sql = "SELECT 
				  c.ci03_idcliente,
				  c.lc01_idUsuario,
				  c.ci03_nombre,
				  DATE_FORMAT(c.ci03_fechaingreso, '%d-%m-%Y') AS ci03_fechaingreso,
				  c.ci03_estadodisponibilidad,
				  c.ci03_fechanacimiento,
				  c.ci03_sexo,
				  c.ci03_especialidad,
				  c.ci03_trabajouno,
				  c.ci03_trabajodos,
				  c.ci03_saldo,
				  u.lc01_nombreUsuario,
				  u.lc01_idUsuario 
				FROM
				  ci03_cliente c 
				  INNER JOIN lc01_usuario u 
				    ON c.lc01_idusuario = u.lc01_idUsuario 
				WHERE c.ci03_idcliente = '".$id."';";
	
		$datos = mysql_query ( $sql );
	
		$datosClienteById = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			
			$entry ['ci03_idcliente'] = $row ['ci03_idcliente'];
			$entry ['lc01_idUsuario'] = $row ['lc01_idUsuario'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['ci03_fechaingreso'] = $row ['ci03_fechaingreso'];
			$entry ['ci03_estadodisponibilidad'] = $row ['ci03_estadodisponibilidad'];
			$entry ['ci03_fechanacimiento'] = $row ['ci03_fechanacimiento'];
			$entry ['ci03_sexo'] = $row ['ci03_sexo'];
			$entry ['ci03_especialidad'] = $row ['ci03_especialidad'];
			$entry ['ci03_trabajouno'] = $row ['ci03_trabajouno'];
			$entry ['ci03_trabajodos'] = $row ['ci03_trabajodos'];
			$entry ['ci03_saldo'] = $row ['ci03_saldo'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
			$entry ['lc01_idUsuario'] = $row ['lc01_idUsuario'];
				
			$datosClienteById [] = $entry;
		}
	
		return $datosClienteById;
	}
	
	public function datosClienteByIdRut($id)
	{
		$sql = "SELECT 
				c.`ci03_nombre`, 
				c.`lc01_idusuario` 
				FROM 
				`ci03_cliente` c INNER JOIN `ci04_rut` r 
				ON 
				c.`ci03_idcliente`=r.`ci03_idcliente`
				WHERE 
				r.`ci04_idrrut`='".$id."';";
	
		$datos = mysql_query ( $sql );
	
		$datosClienteByIdRut = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['lc01_idusuario'] = $row ['lc01_idusuario'];
	
			$datosClienteByIdRut [] = $entry;
		}
	
		return $datosClienteByIdRut;
	}

	public function modificarCliente($data)
	{
		$sql = "UPDATE ci03_cliente SET				
				ci03_nombre='" . $data ['nombreClienteEdit'] . "',
				lc01_idusuario = '" . $data ['idUsuarioEdit'] . "'						
				WHERE ci03_idcliente = '" . $data ['idClienteEdit'] . "'";
			
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function verificarClienteById($id)
	{	
		$sql="SELECT * FROM `ci03_cliente` WHERE `ci03_idcliente` = '".$id."' ";
	
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function verificarClienteByNombre($nombre)
	{
		$sql="SELECT * FROM `ci03_cliente` WHERE `ci03_nombre` = '".$nombre."' ";
	
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function eliminarCliente($idCliente){
	
		$sql="UPDATE ci03_cliente SET ci03_estadodisponibilidad = '0' WHERE ci03_idcliente='".$idCliente."';";
	
		$res=mysql_query($sql);
	
		if($res){
			return true;
		}else{
			return false;
		}
	
	}
	
	public function realizaDevolucion($idCliente,$montoDevolucion,$saldo){
	
		
		$sql="	UPDATE ci03_cliente 
				SET ci03_saldo = ci03_saldo - $montoDevolucion
				WHERE ci03_idcliente='".$idCliente."';";
	
		$sql .=" INSERT into ci15_devolucion (
						ci03_idCliente,
						ci15_montoDevolucion,
						ci15_saldoAnterior,
						ci15_fechaDevolucion)
				VALUES(
						'$idCliente',
						'$montoDevolucion',
						'$saldo',
						CURRENT_TIMESTAMP
				);";
		
		
		$res = mysqli_multi_query ($this->_link, $sql );
	
		if($res){
			return true;
		}else{
			return false;
		}
	
	}
	
	public function traeSaldo($idCliente){
	
		$sql="SELECT ci03_saldo FROM `ci03_cliente` WHERE `ci03_idcliente` = '".$idCliente."' ";
	
		$res = mysql_query($sql);
		
		$fila = mysql_fetch_row($res);
		
		if($fila)
		{
			return $fila[0];
		}
		else
		{
			return false;
		}
	
	}
	
	public function verificaCobrosPendientesCliente($idCliente)
	{
		$sql="SELECT 
			  `ci05_cobroindividual`.`ci05_idcobroindividual` AS id_cobro 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci05_cobroindividual` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci05_cobroindividual`.`ci04_idrrut` 
			WHERE `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' 
			  AND `ci05_cobroindividual`.`ci53_idestadocobro` = '1' 
			UNION
			SELECT 
			  `ci06_honorario`.`ci06_idhonorario` AS id_cobro 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci06_honorario` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci06_honorario`.`ci04_idrrut` 
			WHERE `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' 
			  AND `ci06_honorario`.`ci53_idestadocobro` = '1' 
			UNION
			SELECT 
			  `ci07_cobromasivo`.`ci07_idcobromasivo` AS id_cobro 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci07_cobromasivo` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			WHERE `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' 
			  AND `ci07_cobromasivo`.`ci53_idestadocobro` = '1';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
