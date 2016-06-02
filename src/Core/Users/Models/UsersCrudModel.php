<?Php
namespace Core\Users\Models;

use Symfony\Component\HttpFoundation\Request;

use Core\Utils\CrudModelInterface;
use Core\Utils\AbstractCrudModels;
use Core\Utils\ResponseObject;

use Core\Users\Entitys\UserEntity;
use Core\Users\DataMapper\UserDataMapper;

use Core\Departments\DataMapper\DepartmentDataMapper;
use Core\Empresas\DataMapper\EmpresaDataMapper;


class UsersCrudModel extends AbstractCrudModels implements CrudModelInterface
{

    public function __construct( Request $request, \Pimple $dbs )
    {
        $this->request = $request;
        $this->dbs = $dbs;
        $this->return = new ResponseObject();


        $this->entity = new UserEntity();
        $this->dataMapper = new UserDataMapper($dbs['kernel']);

        $this->idRegistro = $request->get('idRegistro');
        $this->module= 'users';

    }
    
    public function loadEditViewInfo(){

        //loading default entity
        $this->setInfo('registroEntity', $this->returnEntity() );
       
        //loading departments
        $depDataMapper = new DepartmentDataMapper($this->dbs['kernel']);
        $this->setInfo('departamentos', $depDataMapper->getAll() );

        //loading empresas
        $empresas = new EmpresaDataMapper($this->dbs['kernel']);
        $this->setInfo('empresas', $empresas->getAll());

        return $this->returnInfo();
    }
    
     public function loadListActionInfo(){
       //loading departments
        $depDataMapper = new DepartmentDataMapper($this->dbs['kernel']);
        $this->setInfo('departamentos', $depDataMapper->getAllWithFilters($this->request) );
       
        return  $this->returnInfo();  // $teste[0]['departamentos'];
    }

    public function editAction(){
       
        $this->setData();
        $this->validateNecessaryData();
        
        if($this->return->hasResponses()){
            return $this->return->getArray();
        }
        try{
            if ($this->isEdit()) {
                $this->dataMapper->update($this->entity, $this->idRegistro);
                $messageOk = 'Usuário alterado com sucesso!';
            } else {
                $this->dataMapper->insert($this->entity);
                $messageOk = 'Usuário adicionado com sucesso!';
            }
        } catch (\Exception $e) {
            $this->return->addResponse('0','Falha na persistência de dados'.$e->getMessage());
            return $this->return->getArray();
        }

        $this->return->addResponse('1',$messageOk);
        return $this->return->getArray();

    }

     public function loadListViewInfo(){
        
        return  $this->returnInfo();  // $teste[0]['departamentos'];
    }

    public function setData()
    {
        $this->entity->setNomeUsuario($this->request->get('nomeUsuario'));
        $this->entity->setEmail($this->request->get('email'));
        $this->entity->setDtNascimento('2014-01-01');
        $this->entity->setCpf($this->request->get('cpf'));
        $this->entity->setRg($this->request->get('rg'));
        $this->entity->setCep($this->request->get('cep'));
        $this->entity->setEndereco($this->request->get('endereco'));
        $this->entity->setNumero($this->request->get('numero'));
        $this->entity->setComplemento($this->request->get('complemento'));
        $this->entity->setBairro($this->request->get('bairro'));
        $this->entity->setUf($this->request->get('uf'));
        $this->entity->setCidade($this->request->get('cidade'));
        $this->entity->setTelefone1($this->request->get('telefone1'));
        $this->entity->setTelefone2($this->request->get('telefone2'));
        $this->entity->setObservacoes($this->request->get('observacoes'));
        $this->entity->setIdDepartamento($this->request->get('idDepartamento'));
        $this->entity->setIdEmpresa($this->request->get('idEmpresa'));
        $this->entity->setIdPerfil($this->request->get('idPerfil'));
        $this->entity->setStatus($this->request->get('status'));
        $this->entity->setUsuario($this->request->get('usuario'));
        if($this->request->get('senha') != 'password'){
          $this->entity->setSenha($this->request->get('senha'));
        }
    }

    public function validateNecessaryData()
    {
        if ($this->entity->getNomeUsuario() == '')
            $campos[] = 'Nome';

        if ($this->entity->getEmail() == '') 
            $campos[] = 'Email';

        if ($this->entity->getIdEmpresa() == '') 
            $campos[] = 'Empresa';

        if ($this->entity->getIdDepartamento() == '') 
            $campos[] = 'Departamento';

        if ($this->entity->getUsuario() == '') 
            $campos[] = 'Nome de Usuário';

        if ($this->entity->getSenha() == '' && $this->request->get('senha') != 'password') 
            $campos[] = 'Senha';

        if (is_array($campos)) {
            foreach($campos as $campo) {
                $mensagem .= '<b>'.$campo.'</b>, ';
            }
            $mensagem = substr($mensagem,0,-2); 
            $this->return->addResponse('2','Campos necessários: '.$mensagem);
        }
       
    }
    
}