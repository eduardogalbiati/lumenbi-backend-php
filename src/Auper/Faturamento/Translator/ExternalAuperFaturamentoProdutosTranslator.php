<?Php
namespace Auper\Faturamento\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperFaturamentoProdutosTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $qtd, $nomeProduto, $valor, $cliente, $valorCusto)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', ' ');
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto]['valorCusto'] += $valorCusto;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto]['produto'] = $nomeProduto;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto]['qtd'] += $qtd;
    }

    public function translate(array $faturas)
    {
        foreach ($faturas as $fatura) {
            $data = $this->explodeDate($fatura['dataref']);
            $vlrTotal = ($fatura['VlrUnit']*$fatura['Qtde']);
            $vlrTotalDesconto = $vlrTotal * (100 - $fatura['Descom'])/100;
            $valorCusto = $fatura['custo']*$fatura['Qtde'];
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $fatura['Qtde'], $fatura['idProduto'], $vlrTotalDesconto, trim($fatura['nomeCliente']), $valorCusto);
        }

        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $produtos) {
                    foreach ($produtos as $produto => $conteudo) {
                        $conteudo['qtd'] = (($conteudo['qtd'] =='0' || $conteudo['qtd']=='')?'1':$conteudo['qtd']);
                        $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => $conteudo['qtd'],
                            'valor' => $conteudo['valor'],
                            'idProduto' => trim($conteudo['produto']),
                            'datahora' => $ano.'-'.$mes.'-'.$dia,
                            'valorCusto' => $conteudo['valorCusto'],
                            'valorLucro' => ( round( ($conteudo['valor']), 2 ) - $conteudo['valorCusto']),
                            'prcLucro' => 1- round( ($conteudo['valorCusto'] /  $conteudo['valor'] ), 2),
                        );
                    }
                }
            }
        }

        return $translated;
    }
}
