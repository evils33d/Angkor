<?php 
// library/My/Controller/Plugin/ACL.php
class MyProject_Controller_Plugin_ACL extends Zend_Controller_Plugin_Abstract
{
    protected $_defaultRole = 'guest';
 
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
        $acl = new MyProject_Acl();
        $mysession = new Zend_Session_Namespace('mysession');
 
        if($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            
           	$perfil = $user->lc02_idPerfil;
            //echo($perfil);
            if(!$acl->isAllowed($perfil, $request->getControllerName() . '::' . $request->getActionName())) {
                $mysession->destination_url = $request->getPathInfo();
 
                return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->setGotoUrl('auth/noauth');
            }
        } else {
            if(!$acl->isAllowed($this->_defaultRole, $request->getControllerName() . '::' . $request->getActionName())) {
                $mysession->destination_url = $request->getPathInfo();
 
                return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->setGotoUrl('auth/login');
            }
        }
    }
}