<?Php
namespace Auper\Vendas\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperVendasClientesVendedoresTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $qtd, $idCliente, $valor, $idVendedor, $valorCusto)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia][$idCliente][$idVendedor]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$idCliente][$idVendedor]['produto'] = $idCliente;
        $this->arrayOrdenado[$ano][$mes][$dia][$idCliente][$idVendedor]['qtd'] += $qtd;
        $this->arrayOrdenado[$ano][$mes][$dia][$idCliente][$idVendedor]['valorCusto'] = $valorCusto;

    }

    public function translate(array $vendas)
    {
        foreach ($vendas as $venda) {
            $data = $this->explodeDate($venda['dataref']);
            $vlrTotal = ($venda['VlrUnit']*$venda['Qtde']);
            $valorCusto = ($venda['Custo']*$venda['Qtde']);
            $vlrTotalDesconto = $vlrTotal;// * (100 - $venda['Descom'])/100;
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $venda['Qtde'], $venda['idCliente'], $vlrTotalDesconto, $venda['idVendedor'], $valorCusto);
        }

        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $clientes) {
                    foreach ($clientes as $idCliente => $vendedores) {
                        foreach ($vendedores as $idVendedor => $conteudo) {
                            $margem = round((($conteudo['valor']-$conteudo['valorCusto']) / $conteudo['valor']),2 );
                            $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => $conteudo['qtd'],
                            'valor' => $conteudo['valor'],
                            'valorCusto' => $conteudo['valorCusto'],
                            'margem' => $margem,
                            //'desconto' => $conteudo['desc'],
                            'idCliente' => $idCliente,
                            'idVendedor' => $idVendedor,
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
