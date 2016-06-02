<?Php
namespace Auper\Clientes\Fetcher;


class ClientesClasseFetcher
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

    public function fetch(array $clientes)
    {

     //var_dump($this->info);die;
      foreach ($clientes as $k => $cliente) {
        $status = $this->getStatusFor($cliente['idCliente']);
        $clientes[$k] += $status;
      }

      return $clientes;

    }

    protected function getStatusFor($idCliente)
    {
     
      if($this->info[$idCliente]['pos'] == ''){
        $pos = '- ';
        $class = '- ';
      }else{
        $pos = (int) $this->info[$idCliente]['pos'];
        $class= $this->info[$idCliente]['class'];
      }
      $arr = array(
        'posicao' => $pos,
        'classe' =>  $class
        );
      return $arr;
    }
}
