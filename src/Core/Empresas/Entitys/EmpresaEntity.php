<?Php
namespace Core\Empresas\Entitys;

class EmpresaEntity 
{
    private $idEmpresa;
    private $nomeEmpresa;
   
    
    public function getIdEmpresa() 
    {
        return $this->idEmpresa;
    }

    public function getNomeEmpresa() 
    {
        return $this->nomeEmpresa;
    }

    public function setNomeDepartamento($nomeEmpresa) 
    {
        $this->nomeEmpresa = $nomeEmpresa;
        return $this;
    }


}