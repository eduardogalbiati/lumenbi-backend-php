<?Php
namespace Auper\Faturamento\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperFaturamentoVendedoresTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $qtd, $idVendedor, $valor, $valorCusto)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', ' ');
        $this->arrayOrdenado[$ano][$mes][$dia][$idVendedor]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$idVendedor]['valorCusto'] += $valorCusto;
        $this->arrayOrdenado[$ano][$mes][$dia][$idVendedor]['idProduto'] = $idVendedor;
        $this->arrayOrdenado[$ano][$mes][$dia][$idVendedor]['qtd'] += $qtd;
    }

    public function translate(array $faturas)
    {
        foreach ($faturas as $fatura) {
            $data = $this->explodeDate($fatura['dataref']);
            $vlrTotal = ($fatura['VlrUnit']*$fatura['Qtde']);
            $vlrTotalDesconto = $vlrTotal * (100 - $fatura['Descom'])/100;
            $valorCusto = $fatura['custo'];
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $fatura['Qtde'], $fatura['idVendedor'], $vlrTotalDesconto, $valorCusto);
        }

        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $vendedores) {
                    foreach ($vendedores as $idProduto => $conteudo) {
                        $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => $conteudo['qtd'],
                            'valor' => $conteudo['valor'],
                            'idVendedor' => trim($conteudo['idProduto']),
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
