<?Php
namespace Auper\Vendedores\Fetcher;


class VendedoresClasseFetcher
{

    protected $info;
    protected $status;

    public function __construct()
    {
      $this->status = array(
        '1' => 'Positivo',
        '2' => 'Negativo',
        '3' => 'Recuperado',
        '4' => 'Novo',
        '5' => 'Regular'
        );
    }

    public function setInfoToFetch(array $info)
    {
      $this->info = $info;
    }

    public function fetch(array $vendedores)
    {

     //var_dump($this->info);die;
      foreach ($vendedores as $k => $vendedor) {
        $status = $this->getStatusFor($vendedor['idVendedor']);
        $vendedores[$k] += $status;
      }

      return $vendedores;

    }

    protected function getStatusFor($idVendedor)
    {
     
      if($this->info[$idVendedor]['pos'] == ''){
        $pos = '- ';
        $class = '- ';
      }else{
        $pos = (int) $this->info[$idVendedor]['pos'];
        $class= $this->info[$idVendedor]['class'];
      }
      $arr = array(
        'posicao' => $pos,
        'classe' =>  $class
        );
      return $arr;
    }
}
