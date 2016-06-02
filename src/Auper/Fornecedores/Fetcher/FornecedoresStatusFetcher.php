<?Php
namespace Auper\Fornecedores\Fetcher;


class FornecedoresStatusFetcher
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

    public function fetch(array $fornecedores)
    {

     //var_dump($this->info);die;
      foreach ($fornecedores as $k => $fornecedor) {
        $status = $this->getStatusFor($fornecedor['idFornecedor']);
        $fornecedores[$k] += $status;
      }

      return $fornecedores;

    }

    protected function getStatusFor($idFornecedor)
    {
     
      $status =  $this->status[$this->info[$idFornecedor]['idStatus']];
      if($this->info[$idFornecedor]['periodoStatus'] != ''){
        $status .= ' Há '.$this->info[$idFornecedor]['periodoStatus'].' Meses';
      }
      return array(
        'status' => $status,
        'idStatus' => $this->info[$idFornecedor]['idStatus'],
        'pStatus' =>  $this->info[$idFornecedor]['periodoStatus']
        );
    }
}
