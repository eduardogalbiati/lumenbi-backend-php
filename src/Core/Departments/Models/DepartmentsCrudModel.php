<?Php
namespace Core\Departments\Models;

use Symfony\Component\HttpFoundation\Request;

use Core\Utils\CrudModelInterface;
use Core\Utils\AbstractCrudModels;
use Core\Utils\ResponseObject;
use Core\Utils\PaginationObject;

use Core\Departments\Entitys\DepartmentEntity;

use Core\Departments\DataMapper\DepartmentDataMapper;



class DepartmentsCrudModel extends AbstractCrudModels implements CrudModelInterface
{

    public function __construct( Request $request, \Pimple $dbs )
    {
        $this->request = $request;
        $this->dbs = $dbs;
        $this->return = new ResponseObject();


        $this->entity = new DepartmentEntity();
        $this->dataMapper = new DepartmentDataMapper($dbs['kernel']);

        $this->idDepartamento = $request->get('idDepartamento');
        $this->module= 'departments';

    }
    
    public function loadEditViewInfo(){

        //loading default entity
        $this->setInfo('registroEntity', $this->returnEntity() );
       
        //loading departments
        $depDataMapper = new DepartmentDataMapper($this->dbs['kernel']);
        $this->setInfo('departamentos', $depDataMapper->getAll() );
       
        return $this->returnInfo();
    }

    public function loadListActionInfo(){
       //loading departments
        $depDataMapper = new DepartmentDataMapper($this->dbs['kernel']);
       // $this->setInfo('departamentos', $depDataMapper->getLimitWithFilters($this->request) );
        $this->setInfo('departamentos', $depDataMapper->getLimitWithFilters($this->request->get('page'),$this->request) );

        $this->setInfo('pagination', new PaginationObject($this->request->get('page') , $depDataMapper->getCount() ) );
        
        return  $this->returnInfo();  // $teste[0]['departamentos'];
    }

    public function loadListViewInfo(){
        
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
                $messageOk = 'Departamento alterado com sucesso!';
            } else {
                $this->dataMapper->insert($this->entity);
                $messageOk = 'Departamento adicionado com sucesso!';
            }
        } catch (\Exception $e) {
            $this->return->addResponse('0','Falha na persistência de dados'.$e->getMessage());
            return $this->return->getArray();
        }

        $this->return->addResponse('1',$messageOk);
        return $this->return->getArray();

    }
    public function setData()
    {
        $this->entity->setNomeDepartamento($this->request->get('NomeDepartamento'));
    
    }

    public function validateNecessaryData()
    {
        if ($this->entity->getNomeDepartamento() == '')
            $campos[] = 'NomeDepartamento';


        if (is_array($campos)) {
            foreach($campos as $campo) {
                $mensagem .= '<b>'.$campo.'</b>, ';
            }
            $mensagem = substr($mensagem,0,-2); 
            $this->return->addResponse('2','Campos necessários: '.$mensagem);
        }
       
    }
    
}