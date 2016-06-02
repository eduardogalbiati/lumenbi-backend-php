<?Php
namespace Auper\Vendas\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\QtdValoresTranslator;

class ExternalAuperVendasProdutosClientesTranslator extends QtdValoresTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $qtd, $nomeProduto, $valor, $idCliente, $valorCusto, $lucro)
    {
        $valor = $valor == '' ? '0' : $valor;
       
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto][$idCliente]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto][$idCliente]['produto'] = $nomeProduto;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto][$idCliente]['qtd'] += $qtd;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto][$idCliente]['valorCusto'] += $valorCusto;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto][$idCliente]['lucro'] = $lucro;

    }

    public function translate(array $vendas)
    {
        foreach ($vendas as $venda) {

            
            $data = $this->explodeDate($venda['dataref']);
           // $vlrTotal = ($venda['VlrUnit']*$venda['Qtde']);
            $vlrTotal = $venda['vlrtotal'];
            $valorCusto = ($venda['Custo']*$venda['Qtde']);
            $vlrTotalDesconto = $vlrTotal;// * (100 - $venda['Descom'])/100;
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $venda['Qtde'], $venda['idProduto'], $venda['vlrtotal'], $venda['idCliente'], $valorCusto, $venda['lucro']);
        }


        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $produtos) {
                    foreach ($produtos as $produto => $clientes) {
                        foreach ($clientes as $cliente => $conteudo) {
                            //$margem = round((($conteudo['valor']-$conteudo['valorCusto']) / $conteudo['valor']),2 );
                            $margem = round($conteudo['lucro'],2 );
                            $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => $conteudo['qtd'],
                            'valor' => $conteudo['valor'],
                            'valorCusto' => $conteudo['valorCusto'],
                            'margem' => $conteudo['margem'],
                            //'desconto' => $conteudo['desc'],
                            'idProduto' => $produto,
                            'idCliente' => $cliente,
                            'datahora' => $ano.'-'.$mes.'-'.$dia,
                        );
                        }
                        
                    }
                }
            }
        }

        return $translated;
    }
}
