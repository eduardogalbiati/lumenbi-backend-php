<?Php

namespace Core\Utils\Services;

use Doctrine\DBAL\Connection;

class FornecedoresExcluidosService{

	private $permissoesModel;
	private $idDepartamento;
	private $app;

	function __construct($app){
		$this->app = $app;
		
	}

	public function getCodigoFornecedores()
	{
		$stmt = $this->app['db']->prepare("SELECT idFornecedor FROM Auper.[dbo].[fornecedoresExcluidos]");
		$stmt->execute();
		$ret = $stmt->fetchAll();
		return $ret;
	}

	public function getNotInQuery($tag)
	{
		$fornecedores = $this->getCodigoFornecedores();

		foreach($fornecedores as $k => $cliente) {
			if($k != 0) {
				$query .= ' AND ';
			}
			$query .= $tag. ' != '.$cliente['idFornecedor'];
		}

		return $query;
	}
	

} 