<?php
class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract 
{
    public function loggedInAs ()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->lc01_nombreUsuario;
            $apellido = $auth->getIdentity()->lc01_apellidoPaternoUsuario;
            $logoutUrl = $this->view->url(array('controller'=>'auth', 'action'=>'logout'), null, true);
            $msg = array("nombre" => $username." ".$apellido , "url_logout" => $logoutUrl);
            return $msg;
        }else{
        	echo "<script language=\"JavaScript\"" .
                 " type=\"text/javascript\">window.location='/index.php/auth/login';" .
                 "</script>";
        	
        	
        	//$this->_helper->redirector('auth', 'index');
        } 

       /* $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'auth' && $action == 'index') {
            return '';
        }
        $loginUrl = $this->view->url(array('controller'=>'auth', 'action'=>'index'));
        return '<a href="'.$loginUrl.'">Login</a>';*/
    }
}