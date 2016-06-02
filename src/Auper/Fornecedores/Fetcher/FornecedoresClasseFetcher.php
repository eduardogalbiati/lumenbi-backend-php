<?Php
namespace Auper\Fornecedores\Fetcher;


class FornecedoresClasseFetcher
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
     
      if($this->info[$idFornecedor]['pos'] == ''){
        $pos = '- ';
        $class = '- ';
      }else{
        $pos = (int) $this->info[$idFornecedor]['pos'];
        $class= $this->info[$idFornecedor]['class'];
      }
      $arr = array(
        'posicao' => $pos,
        'classe' =>  $class
        );
      return $arr;
    }
}
