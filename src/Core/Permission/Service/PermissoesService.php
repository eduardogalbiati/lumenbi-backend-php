<?Php

namespace Core\Permission\Service;

use \Core\Permission\Model\PermissoesModel;

class PermissoesService{

	private $permissoesModel;
	private $idDepartamento;
	private $app;

	function __construct($app){
		$this->app = $app;
		$this->permissoesModel = new permissoesModel($app['dbs']['kernel']);
		//print_R($app['security']->getToken()->getUser());die;
		$this->idDepartamento = $app['security']->getToken()->getUser()->getIdDepartamento();
	}

	public function hasViewPermissionForModule($module)
	{
		return $this->permissoesModel->consultaPermissao($module, $this->idDepartamento, '1');
	}
	public function hasCreatePermissionForModule($module)
	{
		return $this->permissoesModel->consultaPermissao($module, $this->idDepartamento, '2');
	}
	public function hasDeletePermissionForModule($module)
	{
		return $this->permissoesModel->consultaPermissao($module, $this->idDepartamento, '3');
	}
	public function hasUpdatePermissionForModule($module)
	{
		return $this->permissoesModel->consultaPermissao($module, $this->idDepartamento, '4');
	}

} 