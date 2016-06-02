<?Php
namespace Auper\Compras\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperComprasProdutosTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayValoresTotais = array();

    private function addValue($ano, $mes, $dia, $idPedido, $valor, $produto, $qtde, $valorRateio)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valorRateio = $valorRateio == '' ? '0' : $valorRateio;
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia][$produto]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$produto]['produto'] = $produto;
        $this->arrayOrdenado[$ano][$mes][$dia][$produto]['qtd'] += $qtde;
        $this->arrayOrdenado[$ano][$mes][$dia][$produto]['valorRateio'] = $valorRateio;
        $this->arrayOrdenado[$ano][$mes][$dia][$produto]['idPedido'] = $idPedido;
        $this->arrayValoresTotais[$ano][$mes][$dia][$idPedido] += $valor;
    }

   

    public function translate(array $compras)
    {

        foreach ($compras as $compra) {
            //Alteracao realizada após reuniçao con gutyo
            $c++;
            if($compra['tipo'] == 'M'){
                continue;
            }
            $data = $this->explodeDate($compra['emissao']);

            $valorParaRateio = ($compra['vlroutros'] + $compra['vlrfrete']);
            //Caso seja apenas itens do mesmo pedido
            $vlrTotal = ($compra['total'] + $compra['vlripi'] + $compra['valoricmsst']);
            $vlrTotalDesconto = $vlrTotal;// * (100 - $compra['pdesc'])/100;
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $compra['idCompra'], $vlrTotalDesconto, trim($compra['idProduto']), $compra['qtde'], $valorParaRateio);

        }


        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $produtos) {
                    foreach ($produtos as $produto => $conteudo) {
                        //rateando o valor
                        $vlrTotal = $this->arrayValoresTotais[$ano][$mes][$dia][$conteudo['idPedido']];
                        $vlrItem = $conteudo['valor'];
                        $repr = $vlrItem / $vlrTotal;
                       
                        $rateio = $conteudo['valorRateio'] * $repr;
                        $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            //'qtd' => count($this->arrayValoresTotais[$ano][$mes][$dia][$produto]),
                            'qtd' => $conteudo['qtd'],
                            'valor' => $conteudo['valor']+$rateio,
                            'idProduto' => trim($conteudo['produto']),
                            'datahora' => $ano.'-'.$mes.'-'.$dia,
                        );
                    }
                }
            }
        }

        return $translated;
    }
}
