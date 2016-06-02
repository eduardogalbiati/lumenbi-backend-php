<?Php
namespace Core\Users\Controllers;

use Core\Utils\CrudControllerInterface;
use Core\Users\Models\UsersCrudModel;

class UserCrudController implements CrudControllerInterface
{
	private $app;

	function __construct($app)
	{
	  $this->app = $app;
	}



	public function editView()
	{
		$userModel = new UsersCrudModel($this->app['request'], $this->app['dbs']);	
		return $this->app['twig']->render('Core/Users/Views/editView.html.twig', $userModel->loadEditViewInfo() );
	}


	public function listView()
	{
		//$DepartmentsModel = new DepartmentsCrudModel($this->app['request'], $this->app['dbs']);	
		//return $this->app['twig']->render('Core/Departments/Views/listView.html.twig', array());
	}


	public function listAction()
	{
		//$DepartmentsModel = new DepartmentsCrudModel($this->app['request'], $this->app['dbs']);	
		//return $this->app['twig']->render('Core/Departments/Views/listAction.html.twig', $DepartmentsModel->loadListActionInfo() );
	}

	public function editAction()
	{
		$userModel = new UsersCrudModel($this->app['request'], $this->app['dbs']);	
		return $this->app->json( $userModel->editAction() );
	}

	public function deleteAction()
	{
		//$userModel = new UsersCrudModel($this->app['request'], $this->app['dbs']);	
		//return $this->app->json( $userModel->saveAction() );
	}


	
}
