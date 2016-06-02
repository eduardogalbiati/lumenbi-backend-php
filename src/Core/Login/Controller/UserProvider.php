<?Php
namespace Core\Login\Controller{

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\DBAL\Connection;
use Core\Login\Model\UserModel;
use Core\Menu\Service\MenuService;


	class UserProvider implements UserProviderInterface
	{
		private $db;
		private $app;

		function __construct($app)
		{
		  $this->db = $app['dbs']['kernel'];
		  $this->app = $app;
		}

		public function loadUserByUsername($username)
		{
			$this->app['monolog']->addDebug('xxxUSERNAME: ' . $username);
			$stmt = $this->db->prepare("SELECT * FROM Usuarios WHERE usuario = :usuario");
            $stmt->bindValue("usuario", $username);
            $stmt->execute();

			if (!$user = $stmt->fetch()) {
				throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
			}
						//loading Menu
			$menuService = new MenuService($this->app);
			$arrayOfItens = $menuService->generateMenuForDepartment($user['idDepartamento']);
			//print_r($arrayOfItens);die;
			$userModel = new UserModel($user['usuario'],$user['senha'], $user['idUsuario'], $user['idDepartamento'],$user['nomeUsuario'], $arrayOfItens);
			//$userModel->setIdDepartamento($user['idDepartamento']);
            return $userModel;
		}

		public function refreshUser(UserInterface $user)
		{
			if (!$user instanceof UserModel) {
				throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
			}

			return $this->loadUserByUsername($user->getUsername());
		}

		public function supportsClass($class)
		{
			return $class === 'Symfony\Component\Security\Core\User\User';
		}
	}
}