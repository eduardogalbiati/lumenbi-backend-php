<?Php

namespace Core\Departments\DataMapper;

use Core\Utils\DataMapper\AbstractDataMapper;
use Core\Utils\DataMapper\DataMapperInterface;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

use Core\Departments\Entitys\DepartmentEntity;
use Core\Utils\Entities\AbstractEntity;

class DepartmentDataMapper extends AbstractDataMapper implements DataMapperInterface 
{
	function __construct( Connection $db)
	{
		$this->db = $db;
	}

	public function insert( AbstractEntity $entity)
	{
		$array = $entity->convertToArray();
		unset($array['nomeDepartamento']);
		$this->insertTableArray('Departamentos',$array);
		
	}
	function update(AbstractEntity $entity, $id)
	{
		$array = $entity->convertToArray();
		unset($array['idDepartamento']);
		
		if ($array['idDepartamento'] == '') {
			unset($array['idDepartamento']);
		}
		$this->updateTableById('Departamentos', $array , array('idDepartamento' => $id));

	}

	public function loadById($idDepartamento)
	{
		$stmt = $this->db->prepare("SELECT 
								    D.*
								  FROM 
								    Departamentos D 
								  WHERE
									idDepartamento = :idDepartamento");

		$stmt->bindValue("idDepartamento",$idDepartamento);
		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS , 'DepartmentEntity');
		if(!$department = $stmt->fetch()){
			throw new \Exception('Departamento não encontrado');
		}
		
		return $department;
	}

	public function getAll()
	{
		$stmt = $this->db->prepare("SELECT 
								    D.*
								  FROM 
								    Departamentos D");

		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS , 'DepartmentEntity');
		//print_r($stmt->fetchAll());die;
		return $stmt->fetchAll();
	}



	public function getLimitWithFilters($page , Request $request, $regsPerPage = '10')
	{

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
	}

}