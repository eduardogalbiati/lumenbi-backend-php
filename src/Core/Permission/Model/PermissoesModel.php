<?Php

namespace Core\Permission\Model;

use \Core\Permission\DataMapper\PermissoesMapper;
use \Core\Permission\Entity\PermissoesEntity;

class PermissoesModel
{

	protected $permMapper;

	function __construct($db)
	{
		$this->permMapper = new PermissoesMapper($db);
	}

	public function consultaPermissao($module , $idDepartamento, $idPermissaoTipo)
	{
		$permEntity = $this->permMapper->loadByModuloDepartPermissao($module, $idDepartamento, $idPermissaoTipo);
		if ($permEntity)
			return true;
		else
			return false;
	}



} 