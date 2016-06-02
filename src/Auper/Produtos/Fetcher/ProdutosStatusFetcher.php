<?Php
namespace Auper\Produtos\Fetcher;


class ProdutosStatusFetcher
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

    public function fetch(array $produtos)
    {

     //var_dump($this->info);die;
      foreach ($produtos as $k => $produto) {
        $status = $this->getStatusFor($produto['idProduto']);
        $produtos[$k] += $status;
      }

      return $produtos;

    }

    protected function getStatusFor($idProduto)
    {
     
      $status =  $this->status[$this->info[$idProduto]['idStatus']];
      if($this->info[$idProduto]['periodoStatus'] != ''){
        $status .= ' Há '.$this->info[$idProduto]['periodoStatus'].' Meses';
      }
      return array(
        'status' => $status,
        'idStatus' => $this->info[$idProduto]['idStatus'],
        'pStatus' =>  $this->info[$idProduto]['periodoStatus']
        );
    }
}
