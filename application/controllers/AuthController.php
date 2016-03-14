<?php
// application/controllers/AuthController.php
class AuthController extends Zend_Controller_Action
{
	public function init()
	{
		$bootstrap = $this->getInvokeArg('bootstrap');
		$this->_config = $bootstrap->getOptions();
		 
		$this->view->urlaplicacion = $this->_config['urlaplicacion'];
		$this->view->nombre_sitio = $this->_config['nombre_sitio'];
		$this->view->skin = $this->_config['skin'];
	}
	
	public function loginAction()
	{
		$this->_helper->layout->setLayout('layoutlogin');
		$users = new Application_Model_DbTable_User();
		//$form = new Application_Form_loginForm();
		//$this->view->form = $form;
		
		if($this->getRequest()->isPost()) 
		{			
			if($_REQUEST['username'] != "" and $_REQUEST['password'] != "")
			{
				
				$data['username'] = $_REQUEST['username'];
				$data['password'] = $_REQUEST['password'];
				
				$auth = Zend_Auth::getInstance();
				
				$authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),'lc01_usuario');
				
				$authAdapter->setIdentityColumn('lc01_usernameUsuario')->setCredentialColumn('lc01_contrasenaUsuario');
				$authAdapter->setIdentity($data['username'])->setCredential($data['password']);
				
				$result = $auth->authenticate($authAdapter);
				
				if($result->isValid()) 
				{
					$storage = new Zend_Auth_Storage_Session();
					
					$storage->write($authAdapter->getResultRowObject());
					
					$mysession = new Zend_Session_Namespace('mysession');
					if(isset($mysession->destination_url)) 
					{
						$url = $mysession->destination_url;
						unset($mysession->destination_url);
						$this->_redirect($url);
					}
					$this->_redirect('sistema/index');
				} 
				else 
				{
					$this->view->errorMessage = "Nombre de usuario o contrasena invalido.";
				}
			}
		}
	}

	public function logoutAction()
	{
		$storage = new Zend_Auth_Storage_Session();
		$storage->clear();
		$this->_redirect('auth/login');
	}
	
	public function noauthAction()
	{
		$this->_helper->layout->setLayout('layoutlogin');
	}
}