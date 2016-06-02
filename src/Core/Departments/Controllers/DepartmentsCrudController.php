<?Php
namespace Core\Departments\Controllers;

use Core\Utils\CrudControllerInterface;
use Core\Departments\Models\DepartmentsCrudModel;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\HttpFoundation\Response;

class DepartmentsCrudController implements CrudControllerInterface
{
	private $app;

	function __construct($app)
	{
	  $this->app = $app;
	}

	public function editView()
	{

		$DepartmentsModel = new DepartmentsCrudModel($this->app['request'], $this->app['dbs']);
		return $this->app['twig']->render('Core/Departments/Views/editView.html.twig', $DepartmentsModel->loadEditViewInfo() );
	}

	public function listView()
	{
		//app.security.token.user.nomeUsuario
		$DepartmentsModel = new DepartmentsCrudModel($this->app['request'], $this->app['dbs']);
		return $this->app['twig']->render('Core/Departments/Views/listView.html.twig', $DepartmentsModel->loadListViewInfo() );

	}

	public function listAction()
	{
		$DepartmentsModel = new DepartmentsCrudModel($this->app['request'], $this->app['dbs']);
		return $this->app['twig']->render('Core/Departments/Views/listAction.html.twig', $DepartmentsModel->loadListActionInfo() );
	}

	public function editAction(){
		//$userModel = new UsersCrudModel($this->app['request'], $this->app['dbs']);
		//return $this->app->json( $userModel->saveAction() );
	}

	public function deleteAction(){
		//$userModel = new UsersCrudModel($this->app['request'], $this->app['dbs']);
		//return $this->app->json( $userModel->saveAction() );
	}


}
