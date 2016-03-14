<?php
class Application_Model_MantenedorUsuarioMapper {
	
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
	
	public function ingresarUsuario($data) 
	{	
		$sql = "INSERT INTO lc01_usuario (
				`lc02_idPerfil`,
				`lc01_idUsuarioCreador`,
				`lc01_nombreUsuario`,
				`lc01_apellidoPaternoUsuario`,
				`lc01_apellidoMaternoUsuario`,				
				`lc01_emailUsuario`,
				`lc01_celularUsuario`,
				`lc01_fechaIngresoUsuario`,				
				`lc01_estadoUsuario`,
				`lc01_usernameUsuario`,
				`lc01_contrasenaUsuario`) 				
			 VALUES('" . $data ['perfil'] . "','0','" . $data ['nombre'] . "','','','" . $data ['email'] . "','','','1','" . $data ['email'] . "','" . $data ['pass'] . "')";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
    public function verificaUsuariobyEmail($email){
    	
    	$sql="SELECT * FROM `lc01_usuario` WHERE `lc01_emailUsuario` = '".$email."' ";  	
    	
    	if(mysql_num_rows(mysql_query($sql))>0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    	
    }
    
    public function verificaUsuariobyId($id){
    	 
    	$sql="SELECT * FROM `lc01_usuario` WHERE `lc01_idUsuario` = '".$id."' ";
    	 
    	if(mysql_num_rows(mysql_query($sql))>0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    	 
    }
	
    public function obtenerEmailUsuario($idCliente)
    {
    	$sql = "SELECT lc01_emailUsuario FROM lc01_usuario WHERE lc01_idUsuario='".$idCliente."';";
    	
    	$datos = mysql_query ( $sql );
    	
    	$emailUsuario="";
    	
    	while ( $row = mysql_fetch_array ( $datos ) )
    	{
    	
    		$emailUsuario=$row['lc01_emailUsuario'];
       	}
    	
    	return $emailUsuario;
    }
    
	public function listadoUsuarios() 
	{	
		$sql = "SELECT 
				  u.lc01_idUsuario AS lc01_idUsuario,
				  u.lc01_nombreUsuario AS lc01_nombreUsuario,
				  u.lc01_emailUsuario AS lc01_emailUsuario,
				  p.lc02_nombrePerfil AS lc02_perfilUsuario 
				FROM
				  `lc01_usuario` u 
				  INNER JOIN `lc02_perfil` p 
				    ON u.`lc02_idPerfil` = p.`lc02_idPerfil`
				WHERE 
				p.`lc02_idPerfil`='1'";			
		
		$listadoUsuarios = mysql_query ( $sql );
		
		$usuarios = array ();
		
		while ( $row = mysql_fetch_array ( $listadoUsuarios ) ) {
			$entry ['lc01_idUsuario'] = $row ['lc01_idUsuario'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
			$entry ['lc02_idPerfil'] = $row ['lc02_perfilUsuario'];	
			$entry ['lc01_emailUsuario']=$row['lc01_emailUsuario'];	
			
			$usuarios [] = $entry;
		}
		
		return $usuarios;
	}	
	
	public function listadoTodosUsuarios()
	{
		$sql = "SELECT
				  u.lc01_idUsuario AS lc01_idUsuario,
				  u.lc01_nombreUsuario AS lc01_nombreUsuario,
				  u.lc01_emailUsuario AS lc01_emailUsuario,
				  p.lc02_nombrePerfil AS lc02_perfilUsuario
				FROM
				  `lc01_usuario` u
				  INNER JOIN `lc02_perfil` p
				    ON u.`lc02_idPerfil` = p.`lc02_idPerfil`";
	
		$listadoUsuarios = mysql_query ( $sql );
	
		$usuarios = array ();
	
		while ( $row = mysql_fetch_array ( $listadoUsuarios ) ) {
			$entry ['lc01_idUsuario'] = $row ['lc01_idUsuario'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
			$entry ['lc02_idPerfil'] = $row ['lc02_perfilUsuario'];
			$entry ['lc01_emailUsuario']=$row['lc01_emailUsuario'];
				
			$usuarios [] = $entry;
		}
	
		return $usuarios;
	}
	
	public function eliminaUsuario($idUsuario){
		
		$sql="DELETE FROM lc01_usuario WHERE lc01_idUsuario='".$idUsuario."'";
		
		$res=mysql_query($sql);
		
		if($res){
			return true;
		}else{
			return false;
		}
		
	}
	
	public function modificarUsuario($data)
	{		
		$sql = "UPDATE lc01_usuario SET 				
				lc02_idPerfil='" . $data ['perfilEdit'] . "',						
				lc01_nombreUsuario = '" . $data ['nombreEdit'] . "',						
				lc01_emailUsuario = '" . $data ['emailEdit'] . "',						
				lc01_usernameUsuario = '" . $data ['emailEdit'] . "',						
				lc01_contrasenaUsuario = '" . $data ['passEdit'] . "' WHERE lc01_idUsuario = '" . $data ['idEdit'] . "'";
			
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function datosUsuariosById($id) 
	{
		$sql = "SELECT * FROM lc01_usuario WHERE lc01_idUsuario='".$id."'";
	
		$datos = mysql_query ( $sql );
	
		$datosUsuarios = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) ) 
		{
			$entry ['lc01_idUsuario'] = $row ['lc01_idUsuario'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
			$entry ['lc02_idPerfil'] = $row ['lc02_idPerfil'];
			$entry ['lc01_emailUsuario']=$row['lc01_emailUsuario'];
			$entry ['lc01_contrasenaUsuario']=$row['lc01_contrasenaUsuario'];
			$datosUsuarios [] = $entry;
		}
	
		return $datosUsuarios;
	}

	public function datosUsuarioEmail($id)
	{
		$sql="SELECT u.`lc01_nombreUsuario`, p.`lc02_nombrePerfil`
			 FROM  `lc01_usuario` u INNER JOIN `lc02_perfil` p ON u.`lc02_idPerfil`=p.`lc02_idPerfil`
			 WHERE u.`lc01_idUsuario`='".$id."';";
	
		$datos = mysql_query ( $sql );
	
		$datosUsuarios = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
			$entry ['lc02_nombrePerfil'] = $row ['lc02_nombrePerfil'];
			$datosUsuarios [] = $entry;
		}
	
		return $datosUsuarios;
	
	}
}
