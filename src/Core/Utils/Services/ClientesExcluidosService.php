<?Php

namespace Core\Utils\Services;

use Doctrine\DBAL\Connection;

class ClientesExcluidosService{

	private $permissoesModel;
	private $idDepartamento;
	private $app;

	function __construct($app){
		$this->app = $app;
		
	}

	public function getCodigoClientes()
	{
		$stmt = $this->app['db']->prepare("SELECT idCliente FROM Auper.[dbo].[clientesExcluidos]");
		$stmt->execute();
		$ret = $stmt->fetchAll();
		return $ret;
	}

	public function getNotInQuery($tag)
	{
		$clientes = $this->getCodigoClientes();

		foreach($clientes as $k => $cliente) {
			if($k != 0) {
				$query .= ' AND ';
			}
			$query .= $tag. ' != '.$cliente['idCliente'];
		}

		return $query;
	}
	

} 