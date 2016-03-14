<?php
class Application_Model_ProveedorMapper {
	
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
	
	
	public function ingresarProveedor($data) 
	{
		$sql = "INSERT INTO ci28_proveedor (ci28_nombreproveedor,ci28_estadoproveedor) VALUES ('" . $data ['nombreProveedor'] . "','1')";

		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
    public function verificaConceptById($id){
    	 
    	$sql="SELECT * FROM `ci28_proveedor` WHERE `ci28_idproveedor` = '".$id."' ";
    	 
    	if(mysql_num_rows(mysql_query($sql))>0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}    	 
    }
    
    public function verificaProveedorByNombre($nombre){
    
    	$sql="SELECT * FROM `ci28_proveedor` WHERE `ci28_nombreproveedor` = '".$nombre."' ";
    
    	if(mysql_num_rows(mysql_query($sql))>0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
       
	public function listadoProveedores() {
		
		$sql = "SELECT ci28_idproveedor,
				CONCAT(UCASE(LEFT(`ci28_nombreproveedor`, 1)), LCASE(SUBSTRING(`ci28_nombreproveedor`, 2))) AS `ci28_nombreproveedor` 
				FROM ci28_proveedor";			
		
		$listadoProveedores = mysql_query ( $sql );
		
		$proveedores = array ();
		
		while ( $row = mysql_fetch_array ( $listadoProveedores ) ) 
		{
			
			$entry ['ci28_idproveedor'] = $row ['ci28_idproveedor'];
			$entry ['ci28_nombreproveedor'] = $row ['ci28_nombreproveedor'];		
			
			$proveedores [] = $entry;
		}
		
		return $proveedores;
	}	
		
	public function eliminarProveedor($idProveedor){
		
		$sql="DELETE FROM ci28_proveedor WHERE ci28_idproveedor='".$idProveedor."'";
		
		$res=mysql_query($sql);
		
		if($res){
			return true;
		}else{
			return false;
		}
		
	}
	
	public function datosConceptoById($id)
	{
		$sql = "SELECT * FROM ci33_conceptocobro WHERE ci33_idconcepto='".$id."'";
	
		$datos = mysql_query ( $sql );
	
		$datosConcepto = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci33_nombre'] = $row ['ci33_nombre'];
			$entry ['ci33_tipo_agrupable'] = $row ['ci33_tipo_agrupable'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			
			$datosConcepto [] = $entry;
		}
	
		return $datosConcepto;
	}
	
	public function modificarProveedor($data)
	{		
		$sql = "UPDATE ci28_proveedor SET ci28_nombreproveedor='".$data['nombreProveedorEdit']."'		
				WHERE ci28_idproveedor = '" . $data ['idProveedor'] . "'";
			
		$res = mysql_query( $sql );
	
		if ($res)
		{
			return true;
		} 
		else
		{
			return false;
		}
	}
	
	public function compensaPagos($data)
	{	
		
		$montoacompensar 	= $data['montoaCompensar'];
		$idMovimiento 		= $data['idMovimiento'];
		$tipoMovimiento 	= $data['tipoMovimiento'];
		$idProveedor 		= $data['idProveedor'];
		$observacion		= $data['observacion'];
	
		$sql="";
			
		$sql .= "INSERT INTO ci26_pagoproveedor(ci28_idproveedor,
												ci26_observacion,
												ci26_monto,
												ci26_tipoMovimiento)
										VALUES ('$idProveedor',
												'$observacion',
												'$montoacompensar',
												'$tipoMovimiento');";
		
		$res = mysql_query($sql);

		$id = mysql_insert_id();
		
		if($id){
			
			$sql="";
			
			$sql .= "INSERT INTO ci08_compensacion (ci26_idpagoproveedor,
													ci08_idMovimientoCartola,
													ci08_montoCompensacion)
											VALUES ('$id',
													'$idMovimiento',
													'$montoacompensar');";		
			
			$sql .= "UPDATE ci17_detallecartola
			SET ci17_montoCompensado = (ci17_montoCompensado+$montoacompensar),
			ci36_idEstadoCompensacion = IF(ci17_montoCompensado = (ci17_cheque_cargo + ci17_deposito_abono),2,3),
			ci17_fechaUltimaModificacion = CURRENT_TIMESTAMP
			WHERE ci17_idDetalleCartola = '$idMovimiento';";
		
		}else{
			return false;
		}

		$res = mysqli_multi_query ($this->_link, $sql );
	
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	
	
	}
	

}
