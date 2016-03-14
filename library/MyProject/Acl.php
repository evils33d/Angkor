<?php
// library/My/Acl.php

/**
 * Access Control Lista, controla la lista de recursos a la cual los roles pueden ingresar.
 * Si se agrega una nueva pagina, se debe definir aqui para poder asignarle el nivel de acceso,
 * de otra forma el recurso no estara disponible.
 * guest es el visitante sin loggin.
 */
class MyProject_Acl extends Zend_Acl 
{
	public function __construct() 
	{
		$this->addRole ( new Zend_Acl_Role ( 'guest' ) )->addRole 
		               ( new Zend_Acl_Role ( 'ejecutivo' ) )->addRole 
		               ( new Zend_Acl_Role ( 'supervisor' ) )->addRole 
					   ( new Zend_Acl_Role ( 'gerente' ) )->addRole
					   ( new Zend_Acl_Role ( 'administrativo remuneraciones' ) )->addRole
					   ( new Zend_Acl_Role ( 'administrativo contraloria' ) )->addRole
					   ( new Zend_Acl_Role ( 'mantenedor' ))->addRole 
					   ( new Zend_Acl_Role ( 'administrador' ));
		
		$this->addRole ( new Zend_Acl_Role ( '1' ), 'ejecutivo' )->addRole 
					   ( new Zend_Acl_Role ( '2' ), 'supervisor' )->addRole
					   ( new Zend_Acl_Role ( '3' ), 'gerente' )->addRole
					   ( new Zend_Acl_Role ( '4' ), 'administrativo remuneraciones' )->addRole
					   ( new Zend_Acl_Role ( '5' ), 'administrativo contraloria' )->addRole
					   ( new Zend_Acl_Role ( '6' ), 'mantenedor' )->addRole 
					   ( new Zend_Acl_Role ( '7' ), 'administrador' )->addRole					  
					   ( new Zend_Acl_Role ( '0' ), 'guest' );
		
		// Add some resources in the form controller::action
		$this->add ( new Zend_Acl_Resource ( 'error::error' ) );
		$this->add ( new Zend_Acl_Resource ( 'auth::login' ) );
		$this->add ( new Zend_Acl_Resource ( 'auth::logout' ) );
		$this->add ( new Zend_Acl_Resource ( 'auth::noauth' ) );
		$this->add ( new Zend_Acl_Resource ( 'sistema::index' ) );
		$this->add ( new Zend_Acl_Resource ( 'sistema::inicio' ) );
		$this->add ( new Zend_Acl_Resource ( 'sistema::ingreso' ) );
		$this->add ( new Zend_Acl_Resource ( 'index::index' ) );	
		
		// Proyecto Angkor
		$this->add ( new Zend_Acl_Resource ( 'cliente::ingresarcliente' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::buscarpornombre' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::detallecliente' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::ingreso' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::listar' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::listarbyejecutivo' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::obtener' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::obtenerbyrut' ) );		
		$this->add ( new Zend_Acl_Resource ( 'cliente::modificar' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::eliminar' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::envioemail' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::envioemailrenta' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::listadoemail' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::obtieneemailasociados' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::datoscobroemail' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::obtienedatosusuarioemail' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::eliminacobro' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::listarbynombrecliente' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::realizadevolucion' ) );
		$this->add ( new Zend_Acl_Resource ( 'cliente::obtieneemailasociadobolteo' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'compensacion::movimientoscartola' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::buscarcobro' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::busquedacobros' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::ingresarnumerofactura' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::ingresarcanje' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::busquedatipocobro' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::listadocobros' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::busquedacobrostipofecha' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::busquedacobrosporestado' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::busquedacobroscompensacion' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::guardacompensacion' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::traspasoasaldocliente' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::busquedadetallecompensacion' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::guardapagoproveedor' ) );
		$this->add ( new Zend_Acl_Resource ( 'compensacion::obtienedatospago' ) );		
		$this->add ( new Zend_Acl_Resource ( 'compensacion::traesaldocliente' ));
				
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::cobroindividual' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::obtenerformapago' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::ingresarcobro' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::obtenercobros' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::obtenerdatoscobrobyid' ) ); 
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::modificarcobro' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::obtenercobrobyidcliente' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::modificar' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::obtenerlistadocobros' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::obtenerlistadocobrospec' ) );		
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::detallecobros' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::modificaestadocobro' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::autorizapagoextraordinario' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::verificaautorizacionpago' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobroindividual::filtramovimientos' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::enviomasivocobro' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::ingresomasivocobro' ) );	
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::listarcobrosrenta' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::listarcobrosprevired' ) );		
		$this->add ( new Zend_Acl_Resource ( 'cobro::obtenerestados' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::listarcobrosf29' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::registracobrorenta' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::registracobroprevired' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::registracobrof29' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::filtrocobrof29' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::filtrocobrorenta' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::filtrocobroprevired' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::datosemailppm' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::envioemailppm' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::datosemailrenta' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::envioemailboleteo' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::generapdfclienteboleteo' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::generapdfclienteboleteomasivo' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::generapdfclienteppm' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::verificainfodesacargapdf' ) );		
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::verificainfodescargamasivapdf' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::generapdfclienteppmmasivo' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::verificametabolteoanioactualcliente' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::verificadatosmasivos' ) );		
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::modificarcobromasivo' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::buscarcobromasivo' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::editarcobromasivo' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::editarcobromasivorenta' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::editarcobromasivoprevired' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::listarcobrosf29pendientes' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::listarcobromasivo' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromasivo::registraimpuestounico' ) );
		
		
		$this->add ( new Zend_Acl_Resource ( 'cobromensualidadservicio::ingresomensualidadservicios' ) );		
		$this->add ( new Zend_Acl_Resource ( 'cobromensualidadservicio::obtienerutmensualidadservicio' ) );
		$this->add ( new Zend_Acl_Resource ( 'cobromensualidadservicio::registracobromensualidadservicio' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'contacto::ingresarcontacto' ) );
		$this->add ( new Zend_Acl_Resource ( 'contacto::ingreso' ) );
		$this->add ( new Zend_Acl_Resource ( 'contacto::listarcontactosbyidcliente' ) );
		$this->add ( new Zend_Acl_Resource ( 'contacto::obtenercontactosbyidcontacto' ) );
		$this->add ( new Zend_Acl_Resource ( 'contacto::eliminar' ) );
		$this->add ( new Zend_Acl_Resource ( 'contacto::modificar' ) );		
		
		$this->add ( new Zend_Acl_Resource ( 'pago::ingresoordendepago' ) );
		$this->add ( new Zend_Acl_Resource ( 'pago::registrarordenpago' ) );		
		$this->add ( new Zend_Acl_Resource ( 'pago::verificacionordendepago' ) );
		$this->add ( new Zend_Acl_Resource ( 'pago::verificacionpecautopago' ) );
		$this->add ( new Zend_Acl_Resource ( 'pago::obtenerordenespago' ) );
		$this->add ( new Zend_Acl_Resource ( 'pago::registrarfolio' ) );
		$this->add ( new Zend_Acl_Resource ( 'pago::registrarfoliopec' ) );
		$this->add ( new Zend_Acl_Resource ( 'pago::obtieneordenpagobyidcobro' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'reporte::cobroyestado' ) );
		$this->add ( new Zend_Acl_Resource ( 'reporte::consolidado' ) );
		$this->add ( new Zend_Acl_Resource ( 'reporte::cartolabancaria' ) );
		$this->add ( new Zend_Acl_Resource ( 'reporte::boleteo' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'rut::datosrut' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::buscarporsociedad' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::ingresardatosrut' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::ingresar' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::listadorutbyidcliente' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::listadorutbyejecutivo' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::listadorutbyfiltro' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::obtenerclavescyidrut' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::listar' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::obtenerrubyid' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::modificar' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::eliminar' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::ingresarmeta' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::listarmeta' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::obtenercuentasbyidrut' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::obtenerdatospersonalesbyidrut' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::obtienerutrenta' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::listadobancos' ) );	 
		$this->add ( new Zend_Acl_Resource ( 'rut::obtinerutrentabyanio' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::obtienetotalcobrospendientes' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'rut::actualizaclaves' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::actualizadatosbancarios' ) );
		$this->add ( new Zend_Acl_Resource ( 'rut::actualizadatospersonales' ) );		
		
		$this->add ( new Zend_Acl_Resource ( 'usuario::mantenedorusuarios' ) );
		$this->add ( new Zend_Acl_Resource ( 'usuario::listar' ) );
		$this->add ( new Zend_Acl_Resource ( 'usuario::listartodos' ) );
		$this->add ( new Zend_Acl_Resource ( 'usuario::eliminar' ) );
		$this->add ( new Zend_Acl_Resource ( 'usuario::ingresar' ) );
		$this->add ( new Zend_Acl_Resource ( 'usuario::obtener' ) );
		$this->add ( new Zend_Acl_Resource ( 'usuario::modificar' ) );		
		
		$this->add ( new Zend_Acl_Resource ( 'concepto::mantenedorconceptocobro' ) );
		$this->add ( new Zend_Acl_Resource ( 'concepto::ingresar' ) );
		$this->add ( new Zend_Acl_Resource ( 'concepto::listar' ) );
		$this->add ( new Zend_Acl_Resource ( 'concepto::listarconceptosmasivos' ) );
		$this->add ( new Zend_Acl_Resource ( 'concepto::eliminar' ) );
		$this->add ( new Zend_Acl_Resource ( 'concepto::obtener' ) );
		$this->add ( new Zend_Acl_Resource ( 'concepto::modificar' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'plantilla::mantenedorplantillacorreo' ) );
		$this->add ( new Zend_Acl_Resource ( 'plantilla::ingresar' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'proveedor::mantenedorproveedores' ) );
		$this->add ( new Zend_Acl_Resource ( 'proveedor::ingresar' ) );
		$this->add ( new Zend_Acl_Resource ( 'proveedor::listar' ) );
		$this->add ( new Zend_Acl_Resource ( 'proveedor::eliminar' ) );
		$this->add ( new Zend_Acl_Resource ( 'proveedor::obtener' ) );
		$this->add ( new Zend_Acl_Resource ( 'proveedor::modificar' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'valores::mantenedorvalores' ) );
		$this->add ( new Zend_Acl_Resource ( 'valores::guardaruf' ) );
		$this->add ( new Zend_Acl_Resource ( 'valores::obteneruf' ) );
		$this->add ( new Zend_Acl_Resource ( 'valores::obtenerufactual' ) );	
		$this->add ( new Zend_Acl_Resource ( 'valores::guardartasa' ) );
		$this->add ( new Zend_Acl_Resource ( 'valores::obtenertasa' ) );
		$this->add ( new Zend_Acl_Resource ( 'valores::valortasaactual' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'valores::listartasas' ) );
		$this->add ( new Zend_Acl_Resource ( 'valores::listarretenciones' ) );
		$this->add ( new Zend_Acl_Resource ( 'valores::valortasaactualmesanio' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'valores::guardaretencion' ) );
		$this->add ( new Zend_Acl_Resource ( 'valores::obtenerretencion' ) );
		$this->add ( new Zend_Acl_Resource ( 'valores::valorretencionactual' ) );		
		
		$this->add ( new Zend_Acl_Resource ( 'sociedad::mantenedorsociedades' ) );
		$this->add ( new Zend_Acl_Resource ( 'sociedad::registrar' ) );
		$this->add ( new Zend_Acl_Resource ( 'sociedad::listar' ) );
		$this->add ( new Zend_Acl_Resource ( 'sociedad::obtenervalorsociedad' ) );
		$this->add ( new Zend_Acl_Resource ( 'cartola::index' ) );
		$this->add ( new Zend_Acl_Resource ( 'cartola::cargacartola' ) );
		$this->add ( new Zend_Acl_Resource ( 'cartola::leecartolaexcel' ) );
		$this->add ( new Zend_Acl_Resource ( 'cartola::detallecartola' ) );
		$this->add ( new Zend_Acl_Resource ( 'cartola::listadocartolas' ) );
		
		
		$this->add ( new Zend_Acl_Resource ( 'impuesto::modificaimpuesto' ) );
		$this->add ( new Zend_Acl_Resource ( 'impuesto::editarimpuesto' ) );
		
		
		/*
		 * Nunca super como configurar los permisos con el login, si sabes, arreglalo,
		 * recuerda tambien modificar el AdminController/indexAction
		 */
		
		$this->add ( new Zend_Acl_Resource ( 'admin::index' ) );
		
		$this->allow ( 'guest', array (
				'error::error',
				'auth::login',
				'auth::logout',
				'auth::noauth',
				'index::index' 
		) );
		
		$this->allow ( array (
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7'				
		), array (
				'sistema::index',
				'sistema::inicio',
				'sistema::ingreso',
				'error::error',
				'auth::login',
				'auth::logout',
				'auth::noauth',
				'index::index'
		) );
		
		
		$this->allow ( array (
				'1',
				'2',
				'3',
		), array (		
								
				//Permisos Ejecutivo, Supervisor, Gerente
				
				'cliente::buscarpornombre',
				'cliente::listar',
				'cliente::listarbyejecutivo',
				'cliente::listarbynombrecliente',
				'cliente::obtener',
				'cliente::detallecliente',
				'cliente::listadoemail',
				'cliente::obtenerbyrut',
				'cliente::obtieneemailasociados',
				'cliente::datoscobroemail',
				'cliente::obtienedatosusuarioemail',
				'cliente::envioemail',
				'cliente::envioemailrenta',
				'cliente::realizadevolucion',
				'cliente::obtieneemailasociadobolteo',
								
				'usuario::listar',				
				
				'rut::buscarporsociedad',
				'rut::listar',
				'rut::listadorutbyejecutivo',
				'rut::listadorutbyidcliente',
				'rut::obtienerutrenta',
				'rut::eliminar',
				'rut::ingresardatosrut',
				'rut::datosrut',
				'rut::listadobancos',
				'rut::ingresar',
				'rut::obtenerrubyid',
				'rut::obtienetotalcobrospendientes',
				'rut::obtenerdatospersonalesbyidrut',
				'rut::obtenercuentasbyidrut',
				'rut::obtenerclavescyidrut',
				'rut::modificar',
				'rut::listarmeta',
				'rut::ingresarmeta',
				'rut::obtinerutrentabyanio',
				
				'rut::actualizaclaves',
				'rut::actualizadatosbancarios',
				'rut::actualizadatospersonales',
				
				'cobro::obtenerestados',
				'cobroindividual::obtenercobrobyidcliente',
				'cobroindividual::cobroindividual',
				'cobroindividual::obtenerformapago',
				'cobroindividual::ingresarcobro',
				'cobroindividual::obtenercobros',
				'cobroindividual::modificaestadocobro',
				'cobroindividual::modificar',
				'cobroindividual::obtenerdatoscobrobyid',
				'cobroindividual::modificarcobro',
				'cobroindividual::obtenerlistadocobros',
				'cobroindividual::obtenerlistadocobrospec',
				'cobroindividual::filtramovimientos',
				
				'cobromasivo::datosemailrenta',
				'cobromasivo::ingresomasivocobro',
				'cobromasivo::filtrocobrof29',
				'cobromasivo::registracobrof29',
				'cobromasivo::listarcobrosf29',
				'cobromasivo::registracobrorenta',
				'cobromasivo::enviomasivocobro',
				'cobromasivo::verificainfodesacargapdf',
				'cobromasivo::datosemailppm',
				'cobromasivo::envioemailppm',
				'cobromasivo::envioemailboleteo',
				'cobromasivo::verificametabolteoanioactualcliente',
				'cobromasivo::verificadatosmasivos',
				'cobromasivo::generapdfclienteppm',
				'cobromasivo::generapdfclienteboleteo',
				'cobromasivo::generapdfclienteppmmasivo',
				'cobromasivo::modificarcobromasivo',
				'cobromasivo::buscarcobromasivo',
				'cobromasivo::editarcobromasivo',				
				'cobromasivo::editarcobromasivorenta',
				'cobromasivo::listarcobrosf29pendientes',
								
				'contacto::ingresarcontacto',
				'contacto::ingreso',
				'contacto::obtenercontactosbyidcontacto',
				'contacto::modificar',
				'contacto::eliminar',
				'contacto::listarcontactosbyidcliente',
				
				'compensacion::listadocobros',
				'compensacion::busquedacobros',
				'compensacion::busquedatipocobro',
				'compensacion::busquedacobrosporestado',
				'compensacion::obtienedatospago',
				'compensacion::busquedacobrostipofecha',
				
				'sociedad::listar',
				
				'concepto::listar',
				'concepto::obtener',
				'concepto::listarconceptosmasivos',
				
				'pago::registrarordenpago',
				'pago::verificacionordendepago',
				'pago::obtenerordenespago',
				'pago::registrarfolio',
				'pago::verificacionpecautopago',
				'pago::registrarfoliopec',
				'pago::ingresoordendepago',
				
				'valores::obtenerufactual',
				'valores::valortasaactual',
				'valores::valorretencionactual',
				'valores::listartasas',
				'valores::listarretenciones',
				'valores::obteneruf',
				'valores::valortasaactualmesanio',
				
				'reporte::cobroyestado',
				'reporte::boleteo',
				'reporte::cartolabancaria',
				'reporte::consolidado',
				
				));
		
		
		$this->allow ( array (
				'3'		
		), array (
			'compensacion::buscarcobro',
			'compensacion::ingresarcanje',
			'cobroindividual::verificaautorizacionpago',
			'compensacion::obtienedatospago',
			'cobroindividual::autorizapagoextraordinario',
			'cobromasivo::registracobrorenta',
			'cobromasivo::listarcobrosrenta',
			'cobromasivo::filtrocobrorenta',
		));		
		
		$this->allow ( array (
				'4'
		), array (
				
				'impuesto::modificaimpuesto',
				'impuesto::editarimpuesto',
				
				'cliente::buscarpornombre',
				'cliente::listar',
				'cliente::listarbyejecutivo',
				'cliente::listarbynombrecliente',
				'cliente::obtener',
				'cliente::detallecliente',
				'cliente::listadoemail',
				'cliente::obtenerbyrut',
				'cliente::obtieneemailasociados',
				'cliente::datoscobroemail',
				'cliente::obtienedatosusuarioemail',
				'cliente::envioemail',
				'cliente::envioemailrenta',
				'cliente::realizadevolucion',
				'cliente::obtieneemailasociadobolteo',
				
				'usuario::listar',		
				
				'sociedad::listar',
				
				'concepto::listar',
				'concepto::obtener',
				'concepto::listarconceptosmasivos',

				'rut::buscarporsociedad',
				'rut::listar',
				'rut::listadorutbyejecutivo',
				'rut::listadorutbyidcliente',
				'rut::obtienerutrenta',
				'rut::eliminar',
				'rut::ingresardatosrut',
				'rut::datosrut',
				'rut::listadobancos',
				'rut::ingresar',
				'rut::obtenerrubyid',
				'rut::obtienetotalcobrospendientes',
				'rut::obtenerdatospersonalesbyidrut',
				'rut::obtenercuentasbyidrut',
				'rut::obtenerclavescyidrut',
				'rut::modificar',
				'rut::listarmeta',
				'rut::ingresarmeta',
				'rut::obtinerutrentabyanio',
				
				'cobro::obtenerestados',
				'cobroindividual::obtenercobrobyidcliente',
				'cobroindividual::cobroindividual',
				'cobroindividual::obtenerformapago',
				'cobroindividual::ingresarcobro',
				'cobroindividual::obtenercobros',
				'cobroindividual::modificaestadocobro',
				'cobroindividual::modificar',
				'cobroindividual::obtenerdatoscobrobyid',
				'cobroindividual::modificarcobro',
				'cobroindividual::obtenerlistadocobros',
				'cobroindividual::obtenerlistadocobrospec',
				'cobroindividual::filtramovimientos',
				
				'cobromasivo::ingresomasivocobro',
				'cobromasivo::filtrocobrof29',
				'cobromasivo::registracobrof29',
				'cobromasivo::listarcobrosf29',
				'cobromasivo::listarcobrosprevired',
				'cobromasivo::filtrocobroprevired',
				'cobromasivo::registracobroprevired',
				'compensacion::listadocobros',
				'compensacion::busquedacobros',
				'compensacion::busquedacobrosporestado',
				'cobromasivo::modificarcobromasivo',
				'cobromasivo::buscarcobromasivo',				
				'cobromasivo::editarcobromasivoprevired',
				'cobromasivo::listarcobrosf29pendientes',
				'cobromasivo::editarcobromasivo',
				'cobromasivo::registraimpuestounico',
				
				'contacto::ingresarcontacto',
				'contacto::ingreso',
				'contacto::obtenercontactosbyidcontacto',
				'contacto::modificar',
				'contacto::eliminar',
				'contacto::listarcontactosbyidcliente',
				
				'valores::obtenerufactual',
				'valores::valortasaactual',
				'valores::valorretencionactual',
				'valores::listartasas',
				'valores::listarretenciones',
				'valores::valortasaactualmesanio',
				
				'reporte::cobroyestado',
				'reporte::cartolabancaria',
		));
		
		$this->allow ( array (
				'5'
		), array (
				'cliente::buscarpornombre',
				'cliente::listar',
				'cliente::listarbyejecutivo',
				'cliente::listarbynombrecliente',
				'cliente::obtener',
				'cliente::detallecliente',
				'cliente::listadoemail',
				'cliente::obtenerbyrut',
				'cliente::obtieneemailasociados',
				'cliente::datoscobroemail',
				'cliente::obtienedatosusuarioemail',
				'cliente::envioemail',
				'cliente::envioemailrenta',
				'cliente::realizadevolucion',
				'cliente::obtieneemailasociadobolteo',
				
				'usuario::listar',		
				
				'sociedad::listar',
				
				'rut::buscarporsociedad',
				'rut::listar',
				'rut::listadorutbyejecutivo',
				'rut::listadorutbyidcliente',
				'rut::obtienerutrenta',
				'rut::eliminar',
				'rut::ingresardatosrut',
				'rut::datosrut',
				'rut::listadobancos',
				'rut::ingresar',
				'rut::obtenerrubyid',
				'rut::obtienetotalcobrospendientes',
				'rut::obtenerdatospersonalesbyidrut',
				'rut::obtenercuentasbyidrut',
				'rut::obtenerclavescyidrut',
				'rut::modificar',
				'rut::listarmeta',
				'rut::ingresarmeta',
				'rut::obtinerutrentabyanio',
				
				'cobro::obtenerestados',
				'cobroindividual::obtenercobrobyidcliente',
				'cobroindividual::cobroindividual',
				'cobroindividual::obtenerformapago',
				'cobroindividual::ingresarcobro',
				'cobroindividual::obtenercobros',
				'cobroindividual::modificaestadocobro',
				'cobroindividual::modificar',
				'cobroindividual::obtenerdatoscobrobyid',
				'cobroindividual::modificarcobro',
				'cobroindividual::obtenerlistadocobros',
				'cobroindividual::obtenerlistadocobrospec',
				'cobroindividual::filtramovimientos',
				
				'contacto::ingresarcontacto',
				'contacto::ingreso',
				'contacto::obtenercontactosbyidcontacto',
				'contacto::modificar',
				'contacto::eliminar',
				'contacto::listarcontactosbyidcliente',
				
				'concepto::listar',
				'concepto::obtener',
				'concepto::listarconceptosmasivos',
				
				'valores::obtenerufactual',
				'valores::valortasaactual',
				'valores::valorretencionactual',
				'valores::obteneruf',
				'valores::valortasaactualmesanio',
				
				'compensacion::movimientoscartola',
				'compensacion::busquedacobroscompensacion',
				'compensacion::movimientoscartola',
				'compensacion::buscarcobro',
				'compensacion::busquedacobros',
				'compensacion::ingresarnumerofactura',
				'compensacion::ingresarcanje',
				'compensacion::busquedatipocobro',
				'compensacion::listadocobros',
				'compensacion::busquedacobrostipofecha',
				'compensacion::busquedacobrosporestado',
				'compensacion::busquedacobroscompensacion',
				'compensacion::guardacompensacion',
				'compensacion::traspasoasaldocliente',
				'compensacion::busquedadetallecompensacion',
				'compensacion::guardapagoproveedor',
				'compensacion::obtienedatospago',
				'compensacion::traesaldocliente',
				
				'pago::ingresoordendepago',
				'pago::registrarordenpago',
				
				'cartola::cargacartola',
				'cartola::leecartolaexcel',
				'cartola::detallecartola',
				'cartola::listadocartolas',
				
				'reporte::cobroyestado',
				'reporte::cartolabancaria',
				
				'cobromensualidadservicio::ingresomensualidadservicios',
				'cobromensualidadservicio::obtienerutmensualidadservicio',
				'cobromensualidadservicio::registracobromensualidadservicio'
		));
		
		$this->allow ( array (
				'6'
		), array (
						// Proyecto Angkor
				'cliente::buscarpornombre',
				'cliente::ingresarcliente',//carga la vista
				'cliente::detallecliente',
				'cliente::ingreso',//realiza el registro en la base de datos
				'cliente::listar',
				'cliente::listarbyejecutivo',
				'cliente::obtener',
				'cliente::obtenerbyrut',
				'cliente::modificar',
				'cliente::eliminar',
				'cliente::listarbynombrecliente',
				
				'usuario::listar',
				
				'valores::mantenedorvalores',
				'valores::guardaruf',
				'valores::obteneruf',
				'valores::obtenerufactual',
				'valores::guardartasa',
				'valores::obtenertasa',
				'valores::valortasaactual',
				'valores::guardaretencion',
				'valores::obtenerretencion',
				'valores::valorretencionactual',
				'valores::valortasaactualmesanio',
				
				'usuario::mantenedorusuarios',
				'usuario::listar',
				'usuario::eliminar',
				'usuario::ingresar',
				'usuario::obtener',
				'usuario::modificar',
				'usuario::listartodos',
				
				'concepto::mantenedorconceptocobro',
				'concepto::ingresar',
				'concepto::listar',
				'concepto::eliminar',
				'concepto::obtener',
				'concepto::modificar',
				'concepto::listarconceptosmasivos',
				
				'proveedor::mantenedorproveedores',
				'proveedor::ingresar',
				'proveedor::listar',
				'proveedor::eliminar',
				'proveedor::obtener',
				'proveedor::modificar',
		));
		
		$this->allow ( array (				
				'7'
		), array (
				
				// Proyecto Angkor
				
				'impuesto::modificaimpuesto',
				'impuesto::editarimpuesto',
				
				'cliente::buscarpornombre',
				'cliente::ingresarcliente',//carga la vista
				'cliente::detallecliente',
				'cliente::ingreso',//realiza el registro en la base de datos
				'cliente::listar',
				'cliente::listarbyejecutivo',
				'cliente::obtener',
				'cliente::obtenerbyrut',
				'cliente::modificar',
				'cliente::eliminar',
				'cliente::envioemail',
				'cliente::envioemailrenta',
				'cliente::listadoemail',
				'cliente::obtieneemailasociados',
				'cliente::datoscobroemail',
				'cliente::obtienedatosusuarioemail',
				'cliente::eliminacobro',
				'cliente::listarbynombrecliente',
				'cliente::realizadevolucion',
				'cliente::obtieneemailasociadobolteo',
				
				'rut::ingresardatosrut',
				'rut::buscarporsociedad',
				'rut::datosrut',
				'rut::ingresar',
				'rut::listadorutbyidcliente',
				'rut::listadorutbyejecutivo',
				'rut::listadorutbyfiltro',
				'rut::listar',
				'rut::obtenerrubyid',
				'rut::modificar',
				'rut::eliminar',
				'rut::ingresarmeta',
				'rut::listarmeta',
				'rut::obtenerclavescyidrut',
				'rut::obtenercuentasbyidrut',
				'rut::obtenerdatospersonalesbyidrut',
				'rut::obtienerutrenta',
				'rut::listadobancos',		
				'rut::obtinerutrentabyanio',
				'rut::obtienetotalcobrospendientes',
				
				'rut::actualizaclaves',
				'rut::actualizadatosbancarios',
				'rut::actualizadatospersonales',				
				
				'compensacion::movimientoscartola',
				'compensacion::buscarcobro',	
				'compensacion::busquedacobros',
				'compensacion::ingresarnumerofactura',
				'compensacion::ingresarcanje',
				'compensacion::busquedatipocobro',
				'compensacion::listadocobros',
				'compensacion::busquedacobrostipofecha',
				'compensacion::busquedacobrosporestado',
				'compensacion::busquedacobroscompensacion',
				'compensacion::guardacompensacion',
				'compensacion::traspasoasaldocliente',
				'compensacion::busquedadetallecompensacion',
				'compensacion::guardapagoproveedor',
				'compensacion::obtienedatospago',
				'compensacion::traesaldocliente',
				
				'cobromasivo::enviomasivocobro',
				'cobromasivo::ingresomasivocobro',	
				'cobromasivo::listarcobrosrenta',
				'cobromasivo::listarcobrosprevired',
				'cobromasivo::registracobrorenta',
				'cobromasivo::listarcobrosf29',
				'cobromasivo::registracobroprevired',
				'cobromasivo::registracobrof29',
				'cobromasivo::filtrocobrof29',
				'cobromasivo::filtrocobrorenta',
				'cobromasivo::filtrocobroprevired',
				'cobromasivo::datosemailppm',
				'cobromasivo::envioemailppm',
				'cobromasivo::datosemailrenta',
				'cobromasivo::envioemailboleteo',
				'cobromasivo::generapdfclienteboleteo',
				'cobromasivo::generapdfclienteboleteomasivo',
				'cobromasivo::generapdfclienteppm',
				'cobromasivo::verificainfodesacargapdf',
				'cobromasivo::verificainfodescargamasivapdf',
				'cobromasivo::generapdfclienteppmmasivo',
				'cobromasivo::verificametabolteoanioactualcliente',
				'cobromasivo::verificadatosmasivos',
				'cobromasivo::modificarcobromasivo',
				'cobromasivo::buscarcobromasivo',
				'cobromasivo::editarcobromasivo',
				'cobromasivo::editarcobromasivorenta',
				'cobromasivo::editarcobromasivoprevired',
				'cobromasivo::listarcobrosf29pendientes',
				'cobromasivo::listarcobromasivo',
				'cobromasivo::registraimpuestounico',
				
				'cobromensualidadservicio::ingresomensualidadservicios',
				'cobromensualidadservicio::obtienerutmensualidadservicio',
				'cobromensualidadservicio::registracobromensualidadservicio',
				
				'cobro::obtenerestados',								
				'cobroindividual::cobroindividual',
				'cobroindividual::obtenerformapago',
				'cobroindividual::ingresarcobro',
				'cobroindividual::obtenercobros',
				'cobroindividual::obtenerdatoscobrobyid',
				'cobroindividual::obtenercobrobyidcliente',
				'cobroindividual::modificarcobro',
				'cobroindividual::modificar',
				'cobroindividual::obtenerlistadocobros',
				'cobroindividual::obtenerlistadocobrospec',
				'cobroindividual::detallecobros',
				'cobroindividual::modificaestadocobro',
				'cobroindividual::autorizapagoextraordinario',
				'cobroindividual::verificaautorizacionpago',
				'cobroindividual::filtramovimientos',
				
				'contacto::ingresarcontacto',
				'contacto::ingreso',
				'contacto::listarcontactosbyidcliente',
				'contacto::obtenercontactosbyidcontacto',
				'contacto::eliminar',
				'contacto::modificar',
				
				'pago::ingresoordendepago',
				'pago::registrarordenpago',
				'pago::verificacionordendepago',
				'pago::verificacionpecautopago',	
				'pago::obtenerordenespago',
				'pago::registrarfolio',
				'pago::registrarfoliopec',
				'pago::obtieneordenpagobyidcobro',
				
				'reporte::cobroyestado',
				'reporte::consolidado',
				'reporte::cartolabancaria',
				'reporte::boleteo',
				
				'usuario::mantenedorusuarios',
				'usuario::listar',
				'usuario::eliminar',
				'usuario::ingresar',
				'usuario::obtener',
				'usuario::modificar',	
				'usuario::listartodos',
				
				'concepto::mantenedorconceptocobro',
				'concepto::ingresar',
				'concepto::listar',
				'concepto::eliminar',
				'concepto::obtener',
				'concepto::modificar',
				'concepto::listarconceptosmasivos',
				
				'plantilla::mantenedorplantillacorreo',
				'plantilla::ingresar',
				
				'proveedor::mantenedorproveedores',
				'proveedor::ingresar',				
				'proveedor::listar',
				'proveedor::eliminar', 
				'proveedor::obtener',
				'proveedor::modificar',
				
				'valores::mantenedorvalores',
				'valores::guardaruf',
				'valores::obteneruf',
				'valores::obtenerufactual',
				'valores::guardartasa',
				'valores::obtenertasa',
				'valores::valortasaactual',
				'valores::guardaretencion',
				'valores::obtenerretencion',				
				'valores::listartasas',
				'valores::listarretenciones',
				'valores::valortasaactualmesanio',
				'valores::valorretencionactual',
				
				'cartola::leecartolaexcel',
				'cartola::cargacartola',
				'cartola::detallecartola',
				'cartola::listadocartolas',
				
				'sociedad::mantenedorsociedades',
				'sociedad::registrar',
				'sociedad::listar',
				'sociedad::obtenervalorsociedad'				
		)
	);
		
		$this->allow ( '7', array (
				'admin::index',
		
		) );
	}
}