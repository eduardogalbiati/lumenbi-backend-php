<?Php
namespace Auper\Faturamento\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperFaturamentoClientesTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $qtd, $idCliente, $valor, $valorCusto)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', ' ');
        $this->arrayOrdenado[$ano][$mes][$dia][$idCliente]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$idCliente]['valorCusto'] += $valorCusto;
        $this->arrayOrdenado[$ano][$mes][$dia][$idCliente]['cliente'] = $idCliente;
        $this->arrayOrdenado[$ano][$mes][$dia][$idCliente]['qtd'] += $qtd;
    }

    public function translate(array $faturas)
    {
        foreach ($faturas as $fatura) {
            $data = $this->explodeDate($fatura['dataref']);
            $vlrTotal = ($fatura['VlrUnit']*$fatura['Qtde']);
            $vlrTotalDesconto = $vlrTotal * (100 - $fatura['Descom'])/100;
            $valorCusto = $fatura['custo'];
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $fatura['Qtde'], $fatura['idCliente'], $vlrTotalDesconto, $valorCusto);
        }

        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $clientes) {
                    foreach ($clientes as $cliente => $conteudo) {
                        $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => $conteudo['qtd'],
                            'valor' => $conteudo['valor'],
                            'idCliente' => trim($conteudo['cliente']),
                            'datahora' => $ano.'-'.$mes.'-'.$dia,
                            'valorCusto' => $conteudo['valorCusto'],
                            'valorLucro' => ($conteudo['valor'] - $conteudo['valorCusto']),
                            'prcLucro' => ($conteudo['valor'] / $conteudo['valorCusto'])-1,
                        );
                    }
                }
            }
        }

        return $translated;
    }
}
