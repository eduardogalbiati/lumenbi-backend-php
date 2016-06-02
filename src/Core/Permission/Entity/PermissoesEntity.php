<?Php

namespace Core\Permission\Entity;

class PermissoesEntity{

	protected $idDepartamento;
	protected $idPermissaoTipo;
	protected $idMenu;

	public function getIdDepartamento()
	{
		return $this->idDepartamento;
	}
	public function setIdDepartamento($idDepartamento)
	{
		if(!is_integer($idDepartamento))
			throw new InvalidArgumentException("idDepartamento deve ser Inteiro", 1);
		$this->idDepartamento = $idDepartamento;
		return $this;
	}

	public function getIdPermissaoTipo()
	{
		return $this->idPermissaoTipo;
	}
	public function setIdPermissaoTipo($idPermissaoTipo)
	{
		if(!is_integer($idPermissaoTipo))
			throw new InvalidArgumentException("idPermissaoTipo deve ser Inteiro", 1);
		$this->idPermissaoTipo = $idPermissaoTipo;
		return $this;
	}

	public function getIdMenu()
	{
		return $this->idMenu;
	}
	public function setIdMenu($idMenu)
	{
		if(!is_integer($idMenu))
			throw new InvalidArgumentException("idMenu deve ser Inteiro", 1);
		$this->idMenu = $idMenu;
		return $this;
	}

} 