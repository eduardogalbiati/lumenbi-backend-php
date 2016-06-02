<?Php

namespace Core\Menu\DataMapper;
use \Doctrine\DBAL\Connection;

class MenuDataMapper
{
	private $db;

	function __construct( Connection $db)
	{
		$this->db = $db;
	}


	function getAll($idDepartamento)
	{
		$stmt = $this->db->prepare("SELECT 
								   M.nomeMenu,
								   M.nomeModulo,
								   M.idMenu,
								   M.defaultPath
							  FROM Permissoes P 
						INNER JOIN Menu M
								ON M.idMenu = P.idMenu
							 WHERE P.idPermissaoTipo = '1'
							   AND P.idPermissaoTipo = :idDepartamento
							   AND M.idMenuPai = '0'");
		$stmt->bindValue("idDepartamento",$idDepartamento);

		$stmt->execute();
		$arrayOfFathers = $stmt->fetchAll();

		foreach ($arrayOfFathers as $i => $father){
			$childrens = $this->getChildren($father['idMenu'], $idDepartamento);

			$arrayOfFathers[$i]['childrens'] = $childrens;
		}

		return $arrayOfFathers;
	}

	function getChildren($menuItem, $idDepartamento)
	{
		$stmt = $this->db->prepare("SELECT 
								   M.nomeMenu,
								   M.nomeModulo,
								   M.defaultPath
							  FROM Permissoes P 
						INNER JOIN Menu M
								ON M.idMenu = P.idMenu
							 WHERE P.idPermissaoTipo = '1'
							   AND P.idPermissaoTipo = :idDepartamento
							   AND M.idMenuPai = :idMenuPai");
		$stmt->bindValue("idDepartamento",$idDepartamento);
		$stmt->bindValue("idMenuPai",$menuItem);
		$stmt->execute();
		$childrens = $stmt->fetchAll();
		return $childrens;
	}
}