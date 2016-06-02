<?Php
namespace Auper\Produtos\Fetcher;


class ProdutosFaturamentoFetcher
{

    protected $info;
    protected $status;

    public function setInfoToFetch(array $info)
    {
      foreach ($info as $tupla)
      {
        $arr[$tupla['idProduto']] = $tupla;
      }
      $this->info = $arr;
    }

    public function fetch(array $produtos)
    {

     //var_dump($this->info);die;
      foreach ($produtos as $k => $produto) {
       //var_dump($this->info[$produto['idProduto']]['avgMargem']/100+1);die;
        $produtos[$k]['faturamento']['margem'] = round($this->info[$produto['idProduto']]['avgMargem'],2);
        $custo = $produto['sumValor'] / ( $this->info[$produto['idProduto']]['avgMargem']/100+1 );
        $custo =  $custo;
        $lucro = $produto['sumValor'] - $custo;
        $produtos[$k]['faturamento']['lucro'] = ($lucro);

      }

      return $produtos;

    }

}
