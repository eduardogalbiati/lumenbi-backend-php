<?Php
namespace Auper\Clientes\Fetcher;


class ClientesStatusFetcher
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
     
      $status =  $this->status[$this->info[$idCliente]['idStatus']];
      if($this->info[$idCliente]['periodoStatus'] != ''){
        $status .= ' Há '.$this->info[$idCliente]['periodoStatus'].' Meses';
      }
      return array(
        'status' => $status,
        'idStatus' => $this->info[$idCliente]['idStatus'],
        'pStatus' =>  $this->info[$idCliente]['periodoStatus']
        );
    }
}
