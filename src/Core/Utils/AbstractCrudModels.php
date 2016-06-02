<?Php
namespace Core\Utils;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCrudModels
{
    protected $request;
    protected $dbs;
    protected $return;

    protected $entity;
    protected $dataMapper; 
    
    protected $idRegistro;
    protected $module;
    protected $returnArray = array();

    protected function isEdit(){
        if ($this->idRegistro) {
            return true;
        }
        return false;
    }
    protected function fetchEntity(){
        try{
            $entity = $this->dataMapper->loadById($this->idRegistro);
        }catch(\Exception $e){
            //Register in LOG
            return false;
        }
        $this->entity = $entity;
    }

    protected function returnEntity()
    {
        if($this->isEdit()){
           $this->fetchEntity();
        }
        return $this->entity;

    }

    protected function returnInfo()
    {
          $this->setInfo('module',$this->module);
          $this->setInfo('idRegistro',$this->idRegistro);
          return $this->returnArray;

    }

    protected function setInfo($index, $info)
    {
        $this->returnArray[$index] = $info;
    }

}