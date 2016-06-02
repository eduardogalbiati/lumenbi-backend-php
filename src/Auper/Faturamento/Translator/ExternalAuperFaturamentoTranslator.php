<?Php
namespace Auper\Faturamento\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperFaturamentoTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $idPed, $valor, $valorCusto)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', ' ');
        $this->arrayOrdenado[$ano][$mes][$dia]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia]['valorCusto'] += $valorCusto;
        $this->arrayQuantidade[$ano][$mes][$dia][$idPed] =1;
    }

    public function translate(array $faturas)
    {
        foreach ($faturas as $fatura) {
            $data = $this->explodeDate($fatura['dataref']);
            $vlrTotal = ($fatura['VlrUnit']*$fatura['Qtde']);
            $vlrCusto = round($fatura['custo'],2);
            $vlrTotalDesconto = $vlrTotal * (100 - $fatura['Descom'])/100;
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $fatura['idPedido'], $vlrTotalDesconto, $vlrCusto);
        }
        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $info) {
                    $translated[] = array(
                        'ano' => $ano,
                        'mes' => $mes,
                        'dia' => $dia,
                        'qtd' => count($this->arrayQuantidade[$ano][$mes][$dia]),
                        'valor' => $info['valor'],
                        'valorCusto' => $info['valorCusto'],
                        'valorLucro' => ($info['valor'] - $info['valorCusto']),
                        'prcLucro' => ($info['valor'] / $info['valorCusto'])-1,
                        'datahora' => $ano.'-'.$mes.'-'.$dia,

                    );
                }
            }
        }

        return $translated;

    }
}
