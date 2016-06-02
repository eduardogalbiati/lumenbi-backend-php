<?Php

namespace Bpms\Teste\DataMapper;

use \Bpms\Teste\Entity\UsuariosEntity;

class UsuariosMapper
{
	private $db;

	function __construct( \Doctrine\DBAL\Connection $db)
	{
		$this->db = $db;
	}

	public function consulta(){
		
			$stmt = $this->db->prepare('SELECT * FROM Usuarios');

            //$stmt = $this->db['bpms']->prepare($sql);
            //$stmt->bindValue("usuario", $username);
            //$stmt->bindValue("senha", '50');
		   $stmt->setFetchMode(\PDO::FETCH_CLASS , '\Bpms\Teste\Entity\UsuariosEntity');
            $stmt->execute();
			if (!$user = $stmt->fetch()) {

				throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
			}
			//$usuarios->setNome($user['nome']);
			//$usuarios->setId($user['id']);
			return $user;
           // $arr[0] ='ROLE_ADMIN';
            //return $arr;
	}

	public function loadById($id)
	{	
			
			$stmt = $this->db->prepare('SELECT * FROM Usuarios WHERE id=:id');
			
            $stmt->bindValue("id", $id);

		    $stmt->setFetchMode(\PDO::FETCH_CLASS , '\Bpms\Teste\Entity\UsuariosEntity');
		
            $stmt->execute();
        	
			if (!$user = $stmt->fetch()) {
				throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
			}

			return $user;

	}
}