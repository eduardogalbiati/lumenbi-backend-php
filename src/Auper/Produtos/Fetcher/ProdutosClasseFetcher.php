<?Php
namespace Auper\Produtos\Fetcher;


class ProdutosClasseFetcher
{

    protected $info;

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
     
      if($this->info[$idProduto]['pos'] == ''){
        $pos = '- ';
        $class = '- ';
      }else{
        $pos = (int) $this->info[$idProduto]['pos'];
        $class= $this->info[$idProduto]['class'];
      }
      $arr = array(
        'posicao' => $pos,
        'classe' =>  $class
        );
      return $arr;
    }
}
