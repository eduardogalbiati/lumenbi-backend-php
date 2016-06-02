<?Php
namespace Auper\Vendas\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperVendasClientesTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $idPedido, $valor, $cliente, $valorCusto, $desc, $lucro)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia][$cliente]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$cliente]['cliente'] = $cliente;
        $this->arrayOrdenado[$ano][$mes][$dia][$cliente]['valorCusto'] += $valorCusto;
        $this->arrayOrdenado[$ano][$mes][$dia][$cliente]['lucro'] = $lucro;
        $this->arrayOrdenado[$ano][$mes][$dia][$cliente]['desc'] = $desc;
        $this->arrayQuantidade[$ano][$mes][$dia][$cliente][$idPedido] = 1;
    }

    public function translate(array $vendas)
    {
        foreach ($vendas as $venda) {
            $data = $this->explodeDate($venda['dataref']);
            $vlrTotal = ($venda['VlrUnit']*$venda['Qtde']);
            $vlrTotalDesconto = $vlrTotal;// * (100 - $venda['Descom'])/100;
            $valorCusto = ($venda['Custo']*$venda['Qtde']);
            $margem = round((($vlrTotal-$valorCusto) / $vlrTotal),2);
            if($venda['VlrDesc'] != 0){
                $desc = round(1 - ($vlrTotal / ($vlrTotal+$venda['VlrDesc'])),2);
            }else{
                $desc = 0;
            }
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $venda['idPedido'], $vlrTotalDesconto, trim($venda['idCliente']), $valorCusto, $desc, $venda['lucro']);
        }

        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $clientes) {
                    foreach ($clientes as $cliente => $conteudo) {
                        $margem = round($conteudo['lucro'],2);//round(1 - ($conteudo['valorCusto'] / $conteudo['valor']),2 );
                        if($margem < -100){
                            //echo 'Valor do custo='.$conteudo['valorCusto'];
                            //echo 'valor dtotal='.$conteudo['valor'];
                            //echo $margem;die;
                        }
                        $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => count($this->arrayQuantidade[$ano][$mes][$dia][$cliente]),
                            'valor' => $conteudo['valor'],
                            'valorCusto' => $conteudo['valorCusto'],
                            'margem' => $margem,
                            'desconto' => $conteudo['desc'],
                            'idCliente' => trim($conteudo['cliente']),
                            'datahora' => $ano.'-'.$mes.'-'.$dia,
                        );
                    }
                }
            }
        }

        return $translated;
    }
}
