<?Php
namespace Core\Utils;
use Symfony\Component\HttpFoundation\Request;

interface CrudControllerInterface
{
	//public function __construct( Request $request, \Pimple $dbs );
	public function editView();
	public function editAction();

	public function listView();
	public function listAction();
	public function deleteAction();
}