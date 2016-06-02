<?Php

namespace Core\Permission\DataMapper;

use \Core\Permission\Entity\PermissoesEntity;
use \Doctrine\DBAL\Connection;

class PermissoesMapper
{
	private $db;

	function __construct( Connection $db)
	{
		$this->db = $db;
	}

	public function loadByModuloDepartPermissao($nomeModulo, $idDepartamento, $idPermissaoTipo ){
		
			$stmt = $this->db->prepare("SELECT 
											p.* FROM Permissoes p
										INNER JOIN Menu m 
											ON m.idMenu = p.idMenu 
										INNER JOIN Departamentos d
											ON d.idDepartamento = p.idDepartamento
										WHERE 
											m.nomeModulo=:nomeModulo
										AND
											p.idDepartamento=:idDepartamento
										AND 
											p.idPermissaoTipo=:idPermissaoTipo");
			
			$stmt->bindValue("nomeModulo", $nomeModulo );
			$stmt->bindValue("idDepartamento", $idDepartamento );
			$stmt->bindValue("idPermissaoTipo", $idPermissaoTipo );

            $stmt->execute();
		    $stmt->setFetchMode(\PDO::FETCH_CLASS , '\Core\Permission\Entity\PermissoesEntity');

            return  $stmt->fetch();
            
		     
	}

	public function loadByModuloDepart($nomeModulo='', $idDepartamento=''){
		
			$stmt = $this->db->prepare("SELECT 
											p.* FROM Permissoes p
										INNER JOIN Menu m 
											ON m.idMenu = p.idMenu 
										INNER JOIN Departamentos d
											ON d.idDepartamento = p.idDepartamento
										WHERE 
											m.nomeModulo=:nomeModulo
											AND
											p.idDepartamento=:idDepartamento");
			
			$stmt->bindValue("nomeModulo", $nomeModulo );
			$stmt->bindValue("idDepartamento", $idDepartamento );

		    $stmt->setFetchMode(\PDO::FETCH_CLASS , '\Core\Permission\Entity\PermissoesEntity');
            $stmt->execute();

			$arrayOfEntitys = $stmt->fetchAll();

			return $arrayOfEntitys;
	}

	

}