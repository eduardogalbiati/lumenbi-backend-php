<?Php

namespace Core\Menu\Service;
use \Core\Menu\DataMapper\MenuDataMapper;

class MenuService
{

	private $app;

	function __construct($app){
		$this->app = $app;
		//$this->permissoesModel = new permissoesModel($app['dbs']['silex-teste']);
		//$this->idDepartamento = $app['security']->getToken()->getUser()->getIdDepartamento();
	}

	public function generateMenuForDepartment($idDepartamento)
	{
		$dataMapper = new MenuDataMapper($this->app['dbs']['kernel']);
		$itensMenuEntity = $dataMapper->getAll($idDepartamento); 
		return $itensMenuEntity;
	}
}