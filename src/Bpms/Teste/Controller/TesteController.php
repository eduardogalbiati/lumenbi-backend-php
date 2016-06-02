<?

namespace Bpms\Teste\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

class TesteController
{
	private $app;
	private $request;

	function __construct(Application $app, Request $request)
	{
		$this->app = $app;
		$this->request = $request;

	}
	function getResponse(){
		//return $this->app['dbs']['teste'];
		$mapper = new \Bpms\Teste\DataMapper\UsuariosMapper($this->app['dbs']['teste']);

		$res = $mapper->consulta();
		//return 'asd';
		$arr['Número do cara'] = $res->getId();
		$arr['Nome do individuo'] = $res->getNome();
		return $arr;
		//return $this->request->query->all();
	}

	function carregaUsuario($id){
		$mapper = new \Bpms\Teste\DataMapper\UsuariosMapper($this->app['dbs']['teste']);

		$res = $mapper->loadById($id);
		//return 'asd';
		$arr['Número do cara'] = $res->getId();
		$arr['Nome do individuo'] = $res->getNome();
		return $arr;
	}
	
}
