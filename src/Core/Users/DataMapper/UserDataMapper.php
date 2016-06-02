<?Php

namespace Core\Users\DataMapper;

use \Doctrine\DBAL\Connection;
use \Core\Users\Entitys\UserEntity;
use \Core\Utils\DataMapper\AbstractDataMapper;

class UserDataMapper extends AbstractDataMapper
{
	function __construct( Connection $db)
	{
		$this->db = $db;
	}


	public function loadById($idUsuario)
	{
		$stmt = $this->db->prepare("SELECT 
								    U.*
								  FROM 
								    Usuarios U 
								  WHERE
									idUsuario = :idUsuario");

		$stmt->bindValue("idUsuario",$idUsuario);
		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS , 'UserEntity');
		if(!$user = $stmt->fetch()){
			throw new \Exception('Usuário não encontrado');
		}
		return $user;
	}


	function insert(UserEntity $entity)
	{
		$array = $entity->convertToArray();
		unset($array['idUsuario']);
		//		var_dump($array);

		$this->insertTableArray('Usuarios',$array);
		/*
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
		*/
	}
	function update(UserEntity $entity, $id)
	{
		$array = $entity->convertToArray();
		unset($array['idUsuario']);
		if ($array['senha'] == '') {
			unset($array['senha']);
		}
		$this->updateTableById('Usuarios', $array , array('idUsuario' => $id));

	}


	public function getLimitWithFilters($page , Request $request, $regsPerPage = '10')
	{
		/*

		Esta funcao foi copiada do Departamentos e precisa ser alterada para funcionar aqui (Issue 1 )


		$queryBuilder = $this->db->createQueryBuilder();

		//Calculating the Offset
		$offset = (($page)?($page*$regsPerPage)-$regsPerPage:0);

		//Adding select from query
		$queryBuilder
			->select('*')
			->from('Departamentos','D');

		//Adding Filters	
		if($request->get('departamento')){
			$queryBuilder
				->where('nomeDepartamento LIKE :nomeDepartamento')
				->setParameter('nomeDepartamento', "%".$request->get('departamento')."%");		
		}
		if($request->get('codigo')){
			$queryBuilder
				->andWhere('D.idDepartamento = :idDepartamento')
				->setParameter('idDepartamento', $request->get('IdDepartamento'));
		}

		//Seting the Count number for pagination
		$this->setCount(clone($queryBuilder));

		//Adding offset for pagination
		$queryBuilder
			->setFirstResult($offset)
			->setMaxResults($regsPerPage);

		//Adding order by	
		$queryBuilder->orderBy($request->get('order_field'), $request->get('order_asc'));

		//Executing Query
		$stmt = $queryBuilder->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS , 'DepartmentEntity');

		return $stmt->fetchAll();
		*/
	}
}