<?php
// application/models/User.php
class Application_Model_User
{
	/*protected $_id;
	protected $_email;
	protected $_password;
	protected $_role;*/	
	
	protected $_lc01_idUsuario;
	protected $_lc02_idPerfil;
	protected $_lc01_emailUsuario;
	protected $_lc01_contrasenaUsuario;
	/*protected $_ap06_idTienda ;
	protected $_ap02_nombreUsuario;
	protected $_ap02_apellidoPaternoUsuario;
	protected $_ap02_apellidoMaternoUsuario;
	protected $_ap02_RutUsuario;	
	protected $_ap02_celularUsuario; 
	protected $_ap02_fechaIngresoUsuario;
	protected $_ap02_usernameUsuario;*/


	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}

	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}

		return $this;
	}

	public function setId($id)
	{
		$this->_lc01_idUsuario = (int) $id;

		return $this;
	}

	public function getId()
	{
		return $this->_lc01_idUsuario;
	}

	
	public function setRole($role)
	{
		$this->_lc02_idPerfil = (string) $role;
	
		return $this;
	}
	
	public function getRole()
	{
		return $this->_lc01_idPerfil;
	}
	
	public function setEmail($email)
	{
		$this->_lc01_emailUsuario = (string) $email;

		return $this;
	}

	public function getEmail()
	{
		return $this->_lc01_emailUsuario;
	}

	public function setPassword($password)
	{
		$this->_lc01_contrasenaUsuario = (string) $password;

		return $this;
	}

	public function getPassword()
	{
		return $this->_lc01_contrasenaUsuario;
	}


}