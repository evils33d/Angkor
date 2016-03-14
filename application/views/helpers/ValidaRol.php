<?php
class Zend_View_Helper_ValidaRol extends Zend_View_Helper_Abstract 
{
    public function validaRol ()
    {
    	/*$request = Zend_Controller_Front::getInstance()->getRequest();
        return $request;*/
    	
    	$perfil = 0;
    	$auth = Zend_Auth::getInstance();
    	
    	if($auth->hasIdentity()) {
    		$user = $auth->getIdentity();
    	
    		$perfil = $user->lc02_idPerfil;
    		
    	}
    	
    	return $perfil;
    }
}