<?Php
namespace Core\Login\Model;

use Symfony\Component\Security\Core\User\UserInterface;

class UserModel implements UserInterface, \Serializable{

    private $idDepartamento;
    private $idUsuario;
    private $nomeUsuario;
    private $itensMenu = array();

	private $username;
    private $password;
    private $salt;
    private $roles = array();

    public function __construct($username, $password, $idUsuario, $idDepartamento, $nomeUsuario, $itensMenu)
    {
        $this->username = $username;
        $this->password = $password;
        $this->idUsuario = $idUsuario;
        $this->idDepartamento = $idDepartamento;
        $this->nomeUsuario = $nomeUsuario;
        $this->itensMenu = $itensMenu;
        $this->roles = array('ROLE_ADMIN');
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

    public function getIdDepartamento()
    {
    	return $this->idDepartamento;
    }

    public function getIdUsuario()
    {
    	return $this->idUsuario;
    }

    public function getNomeUsuario()
    {
    	return $this->nomeUsuario;
    }

    public function getItensMenu()
    {
        return $this->itensMenu;
    }

	 public function serialize()
    {
        return serialize(array(
            $this->idUsuario,
            $this->username,
            $this->password,
            // see section on salt below
            $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->idUsuario,
            $this->username,
            $this->password,
            // see section on salt below
             $this->salt
        ) = unserialize($serialized);
    }
    
}