<?php
class Application_Model_CartolaMapper 
{
	protected $_dbTable;
	
	public function __construct() 
	{
		$bootstrap = Zend_Controller_Front::getInstance ()->getParam ( 'bootstrap' );
		$options = $bootstrap->getOptions ();
		
		$host = $options ['resources'] ['db'] ['params'] ['host'];
		$username = $options ['resources'] ['db'] ['params'] ['username'];
		$password = $options ['resources'] ['db'] ['params'] ['password'];
		$dbname = $options ['resources'] ['db'] ['params'] ['dbname'];
		
		$link = mysql_connect ( $host, $username, $password ) or die ( mysql_error () );
		mysql_select_db ( $dbname, $link ) or die ( mysql_error () );
		
		$sql1 = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
		
		mysql_query($sql1);
	}
	
	
	public function cargaDocumento($datos){

		$sql = "INSERT INTO ci16_cartola (ci62_idCuenta, ci14_nombreArchivo)
				VALUES ('".$datos['cuenta']."','".$datos['nombre_archivo']."')";
		
		$res = mysql_query($sql);
		
		if($res){
			return mysql_insert_id();
		}else{
			return false;
		}
		
		
	}
	
	
	public function cargaLineaDetalle($idCartola, $datos){
	
		$sql = "INSERT INTO ci17_detallecartola (ci16_idcartola, 
												 ci17_fechaMovimiento,
												 ci17_detalleMovimiento,
												 ci17_cheque_cargo,
												 ci17_deposito_abono,
												 ci17_saldo,
												 ci17_nroDocumento,
												 ci17_trn,
												 ci17_caja,
												 ci17_sucursal,
												 ci36_idEstadoCompensacion,
												 ci17_montoCompensado)
										VALUES ('".$idCartola."',
										 		'".$datos[0]."',
										 	    '".$datos[1]."',
										 	    '".number_format($datos[2], 0, '', '')."',
										 	    '".number_format($datos[3], 0, '', '')."',
										 	    '".number_format(str_replace("+","",$datos[4]),0,'','')."',
										 	    '".$datos[5]."',
										 	    '".$datos[6]."',
										 	    '".$datos[7]."',
										 	    '".$datos[8]."',
										 	    '1',
										 	    '0'										 	    												 				
										 		);";
		$res = mysql_query($sql);
	
		if($res){
			return true;
		}else{
			return false;
		}
	
	
	}
	
	public function verDetalle($idCartola){
		
		$sql = "SELECT 
				  ci17_detallecartola.ci17_idDetalleCartola,
				  ci17_detallecartola.ci16_idcartola,
				  ci17_detallecartola.ci17_fechaMovimiento,
				  ci17_detallecartola.ci17_detalleMovimiento,
				  ci17_detallecartola.ci17_cheque_cargo,
				  ci17_detallecartola.ci17_deposito_abono,
				  ci17_detallecartola.ci17_saldo,
				  ci17_detallecartola.ci17_nroDocumento,
				  ci17_detallecartola.ci17_trn,
				  ci17_detallecartola.ci17_caja,
				  ci17_detallecartola.ci17_sucursal,
				  ci17_detallecartola.ci36_idEstadoCompensacion,
				  ci36_estadocompensacion.ci36_nombreEstado,
				  ci17_detallecartola.ci17_montoCompensado 
				FROM
				  ci17_detallecartola 
				  INNER JOIN ci36_estadocompensacion ON ci36_estadocompensacion.ci36_idEstadoCompensacion = ci17_detallecartola.ci36_idEstadoCompensacion
				WHERE ci17_detallecartola.ci16_idcartola = $idCartola ";
		
		$res = mysql_query ( $sql );
		
		$detalle = array ();
		
		while ( $row = mysql_fetch_array ( $res ) ) {
		
		 	$valor ['ci17_idDetalleCartola']=  $row ['ci17_idDetalleCartola'];
		 	$valor ['ci16_idcartola'] = $row ['ci16_idcartola'];
		 	$valor ['ci17_fechaMovimiento'] = $row ['ci17_fechaMovimiento'];
		 	$valor ['ci17_detalleMovimiento'] =  $row ['ci17_detalleMovimiento'];
		  	$valor ['ci17_cheque_cargo'] = $row ['ci17_cheque_cargo'];
		  	$valor ['ci17_deposito_abono'] = $row ['ci17_deposito_abono'];
		  	$valor ['ci17_saldo'] = $row ['ci17_saldo'];
		  	$valor ['ci17_nroDocumento'] = $row ['ci17_nroDocumento'];
		  	$valor ['ci17_trn'] = $row ['ci17_trn'];
		  	$valor ['ci17_caja'] = $row ['ci17_caja'];
		  	$valor ['ci17_sucursal'] = $row ['ci17_sucursal'];
		  	$valor ['ci36_idEstadoCompensacion'] = $row ['ci36_idEstadoCompensacion'];
		  	$valor ['ci36_nombreEstado'] = $row ['ci36_nombreEstado'];
		  	$valor ['ci17_montoCompensado'] = $row ['ci17_montoCompensado'];
			
		$detalle [] = $valor;
		}
		
		return $detalle;
		
		
	}
	
	
	public function obtenerCartolas(){
	
		$sql = "SELECT 
				ci16_cartola.ci16_idcartola,
				ci16_cartola.ci62_idCuenta,
				ci62_cuentaCteAngkor.ci62_nroCuenta,
				ci16_cartola.ci14_nombreArchivo,
				ci16_cartola.ci14_fechaCarga
				FROM ci16_cartola
				INNER JOIN ci62_cuentaCteAngkor ON ci62_cuentaCteAngkor.ci62_idCuenta = ci16_cartola.ci62_idCuenta";
	
		$res = mysql_query ( $sql );
	
		$detalle = array ();
	
		while ( $row = mysql_fetch_array ( $res ) ) {
	
			$valor ['ci16_idcartola']=  $row ['ci16_idcartola'];
			$valor ['ci62_idCuenta'] = $row ['ci62_idCuenta'];
			$valor ['ci62_nroCuenta'] = $row ['ci62_nroCuenta'];
			$valor ['ci14_nombreArchivo'] =  $row ['ci14_nombreArchivo'];
			$valor ['ci14_fechaCarga'] = $row ['ci14_fechaCarga'];
				
			$detalle [] = $valor;
		}
	
		return $detalle;
	
	
	}
	
	public function obtenerUltimaCartola(){
		
		$sql = "SELECT 
				  MAX(ci16_idcartola) ultima_cartola 
				FROM
				  ci16_cartola ";
		
		$res = mysql_query ( $sql );
		
		$id = mysql_result ($res,0);
		
		return $id;
	}

	
	public function verDetallePorFiltro($filtros){
		
		$where = "";
		if($filtros['estado'] != ""){
			$where .= " AND ci17_detallecartola.ci36_idEstadoCompensacion = ".$filtros['estado']." ";		
		}
		
		
		if($filtros['fecha'] != ""){
			
			$fecha = explode("-",$filtros['fecha']);
			$where .= " AND STR_TO_DATE(ci17_detallecartola.ci17_fechaMovimiento, '%d/%m/%Y') BETWEEN STR_TO_DATE('".$fecha[0]."','%d/%m/%Y') AND STR_TO_DATE('".$fecha[1]."','%d/%m/%Y')";
		}
		

		$sql = "SELECT
				  ci17_detallecartola.ci17_idDetalleCartola,
				  ci17_detallecartola.ci16_idcartola,
				  ci17_detallecartola.ci17_fechaMovimiento,
				  ci17_detallecartola.ci17_detalleMovimiento,
				  ci17_detallecartola.ci17_cheque_cargo,
				  ci17_detallecartola.ci17_deposito_abono,
				  ci17_detallecartola.ci17_saldo,
				  ci17_detallecartola.ci17_nroDocumento,
				  ci17_detallecartola.ci17_trn,
				  ci17_detallecartola.ci17_caja,
				  ci17_detallecartola.ci17_sucursal,
				  ci17_detallecartola.ci36_idEstadoCompensacion,
				  ci36_estadocompensacion.ci36_nombreEstado,
				  ci17_detallecartola.ci17_montoCompensado
				FROM
				  ci17_detallecartola
				  INNER JOIN ci36_estadocompensacion ON ci36_estadocompensacion.ci36_idEstadoCompensacion = ci17_detallecartola.ci36_idEstadoCompensacion
				WHERE 1 = 1 ";
		
		$sql .= $where;
		
		$res = mysql_query ( $sql );
		
		$detalle = array ();
		
		while ( $row = mysql_fetch_array ( $res ) ) {
		
			$valor ['ci17_idDetalleCartola']=  $row ['ci17_idDetalleCartola'];
			$valor ['ci16_idcartola'] = $row ['ci16_idcartola'];
			$valor ['ci17_fechaMovimiento'] = $row ['ci17_fechaMovimiento'];
			$valor ['ci17_detalleMovimiento'] =  $row ['ci17_detalleMovimiento'];
			$valor ['ci17_cheque_cargo'] = $row ['ci17_cheque_cargo'];
			$valor ['ci17_deposito_abono'] = $row ['ci17_deposito_abono'];
			$valor ['ci17_saldo'] = $row ['ci17_saldo'];
			$valor ['ci17_nroDocumento'] = $row ['ci17_nroDocumento'];
			$valor ['ci17_trn'] = $row ['ci17_trn'];
			$valor ['ci17_caja'] = $row ['ci17_caja'];
			$valor ['ci17_sucursal'] = $row ['ci17_sucursal'];
			$valor ['ci36_idEstadoCompensacion'] = $row ['ci36_idEstadoCompensacion'];
			$valor ['ci36_nombreEstado'] = $row ['ci36_nombreEstado'];
			$valor ['ci17_montoCompensado'] = $row ['ci17_montoCompensado'];
				
			$detalle [] = $valor;
		}
		
		return $detalle;
		
		
		
		
	}
	
	
	public function verDetalleAll(){
	
		$sql = "SELECT
		ci17_detallecartola.ci17_idDetalleCartola,
		ci17_detallecartola.ci16_idcartola,
		ci17_detallecartola.ci17_fechaMovimiento,
		ci17_detallecartola.ci17_detalleMovimiento,
		ci17_detallecartola.ci17_cheque_cargo,
		ci17_detallecartola.ci17_deposito_abono,
		ci17_detallecartola.ci17_saldo,
		ci17_detallecartola.ci17_nroDocumento,
		ci17_detallecartola.ci17_trn,
		ci17_detallecartola.ci17_caja,
		ci17_detallecartola.ci17_sucursal,
		ci17_detallecartola.ci36_idEstadoCompensacion,
		ci36_estadocompensacion.ci36_nombreEstado,
		ci17_detallecartola.ci17_montoCompensado
		FROM
		ci17_detallecartola
		INNER JOIN ci36_estadocompensacion ON ci36_estadocompensacion.ci36_idEstadoCompensacion = ci17_detallecartola.ci36_idEstadoCompensacion";
	
		$res = mysql_query ( $sql );
	
		$detalle = array ();
	
		while ( $row = mysql_fetch_array ( $res ) ) {
	
		$valor ['ci17_idDetalleCartola']=  $row ['ci17_idDetalleCartola'];
		$valor ['ci16_idcartola'] = $row ['ci16_idcartola'];
		 	$valor ['ci17_fechaMovimiento'] = $row ['ci17_fechaMovimiento'];
		 	$valor ['ci17_detalleMovimiento'] =  $row ['ci17_detalleMovimiento'];
			 	$valor ['ci17_cheque_cargo'] = $row ['ci17_cheque_cargo'];
			 			$valor ['ci17_deposito_abono'] = $row ['ci17_deposito_abono'];
			 					$valor ['ci17_saldo'] = $row ['ci17_saldo'];
		  	$valor ['ci17_nroDocumento'] = $row ['ci17_nroDocumento'];
		  	$valor ['ci17_trn'] = $row ['ci17_trn'];
			  	$valor ['ci17_caja'] = $row ['ci17_caja'];
			  			$valor ['ci17_sucursal'] = $row ['ci17_sucursal'];
			  			$valor ['ci36_idEstadoCompensacion'] = $row ['ci36_idEstadoCompensacion'];
		  	$valor ['ci36_nombreEstado'] = $row ['ci36_nombreEstado'];
			  	$valor ['ci17_montoCompensado'] = $row ['ci17_montoCompensado'];
			  		
		$detalle [] = $valor;
		}
	
		return $detalle;
	
	
		}

}


























