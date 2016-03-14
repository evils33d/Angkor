<?php 
// application/forms/LoginForm.php
class Application_Form_LoginForm extends Zend_Form
{
	public function init()
	{
		
		$this->setName("loginform");
		$this->setMethod('post');
		
		
		$username = $this->createElement('text','username', array(
           'placeholder' => 'Nombre Usuario',
		   'title' => 'Ingresar el nombre de usuario'
			));
		$username->setLabel('')
		->setRequired(true);
		

		$password = $this->createElement('password','password', array(
           'placeholder' => utf8_encode('Contraseña'),
		   'title' => utf8_encode('Ingresar la contraseña')
			));
		$password->setLabel('')
		->setRequired(true);

		$signin = $this->createElement('submit','signin', array(
           'class' => "ingreso w1"
			));
		$signin->setLabel('INGRESAR')
		->setIgnore(true);

		$this->addElements(array(
				$username,
				$password,
				$signin,
		));
	}
}