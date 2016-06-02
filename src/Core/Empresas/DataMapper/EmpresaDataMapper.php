<?Php

namespace Core\Empresas\DataMapper;

use \Doctrine\DBAL\Connection;
use \Core\Empresas\Entitys\EmpresaEntity;

class EmpresaDataMapper
{
	private $db;

	function __construct( Connection $db)
	{
		$this->db = $db;
	}


	public function loadById($idEmpresa)
	{
		$stmt = $this->db->prepare("SELECT 
								    E.*
								  FROM 
								    Empresas E 
								  WHERE
									idEmpresa = :idEmpresa");

		$stmt->bindValue("idEmpresa",$idEmpresa);
		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS , 'EmpresaEntity');
		if(!$item = $stmt->fetch()){
			throw new \Exception('Empresa não encontrado');
		}
		return $item;
	}

	function getAll()
	{
		$stmt = $this->db->prepare("SELECT 
								    E.*
								  FROM 
								    Empresas E");

		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS , 'EmpresaEntity');
		return $stmt->fetchAll();
	}

}