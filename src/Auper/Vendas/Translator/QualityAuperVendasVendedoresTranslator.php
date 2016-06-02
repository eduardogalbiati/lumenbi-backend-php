<?Php
namespace Auper\Vendas\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperVendasVendedoresTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $idPedido, $valor, $idVendedor, $valorCusto, $lucro)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia][$idVendedor]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$idVendedor]['idVendedor'] = $idVendedor;
        $this->arrayOrdenado[$ano][$mes][$dia][$idVendedor]['valorCusto'] += $valorCusto;
        $this->arrayOrdenado[$ano][$mes][$dia][$idVendedor]['lucro'] = $lucro;
        $this->arrayQuantidade[$ano][$mes][$dia][$idVendedor][$idPedido] = 1;

    }

    public function translate(array $vendas)
    {
        foreach ($vendas as $venda) {

            $data = $this->explodeDate($venda['dataref']);
            $vlrTotal = ($venda['VlrUnit']*$venda['Qtde']);
            $valorCusto = ($venda['Custo']*$venda['Qtde']);
            $vlrTotalDesconto = $vlrTotal;// * (100 - $venda['Descom'])/100;
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $venda['idPedido'], $vlrTotalDesconto, $venda['idVendedor'],$valorCusto, $venda['lucro']);
        }

        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $vendedores) {
                    foreach ($vendedores as $vendedor => $conteudo) {
                            $margem = round($conteudo['lucro'],2);//round((($conteudo['valor']-$conteudo['valorCusto']) / $conteudo['valor']),2 );
                        $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => count($this->arrayQuantidade[$ano][$mes][$dia][$vendedor]),
                            'valor' => $conteudo['valor'],
                            'valorCusto' => $conteudo['valorCusto'],
                            'margem' => $margem,
                            'idVendedor' => (($conteudo['idVendedor'] == '')?'99999':$conteudo['idVendedor']),
                            'datahora' => $ano.'-'.$mes.'-'.$dia,

                        );
                    }
                }
            }
        }

        return $translated;
    }
}
