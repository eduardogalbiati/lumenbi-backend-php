<?Php
namespace Auper\Estoque\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ShEstoqueTranslator extends AbstractTranslator
{
/*
    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $qtd, $nomeProduto, $valor, $cliente, $valorCusto, $lucro)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto]['valorCusto'] += $valorCusto;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto]['lucro'] = $lucro;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto]['produto'] = $nomeProduto;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto]['qtd'] += $qtd;
    }
*/
    public function translate(array $estoque)
    {

        foreach ($estoque as $item) {
            $arr = array(
                'qtd' => $item['ESTOQUE_DISP'],
                'qtdOriginal' => $item['ESTOQUE_DISP'],
                'valorCusto' => round($item['CUSTO'],2),
                'valor' => round($item['VENDA'],2),
                'ncm' => $item['NC_MERCOSUL'],
                'descricao' => $item['NOME'],

                );

            if($item['VENDA'] == 0 || $item['VENDA'] == '' || $item['CUSTO'] == 0 || $item['CUSTO'] == '' ){

            }else{
                $arr['margem'] = round($item['CUSTO'] / $item['VENDA'], 2);
            }

            $translated[] = $arr;
        }


       /* foreach ($vendas as $venda) {
            $data = $this->explodeDate($venda['dataref']);
            $vlrTotal = ($venda['VlrUnit']*$venda['Qtde']);
            $vlrTotalDesconto = $vlrTotal;// * (100 - $venda['Descom'])/100;
            $valorCusto = ($venda['Custo']*$venda['Qtde']);
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $venda['Qtde'], $venda['idProduto'], $vlrTotalDesconto, trim($venda['nomeCliente']), $valorCusto, $venda['lucro']);
        }

        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $produtos) {
                    foreach ($produtos as $produto => $conteudo) {
                            //$margem = round((($conteudo['valor']-$conteudo['valorCusto']) / $conteudo['valor']),2 );
                            $margem = round($conteudo['lucro'],2 );
                        $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => $conteudo['qtd'],
                            'valor' => $conteudo['valor'],
                            'valorCusto' => $conteudo['valorCusto'],
                            'margem' => $margem,
                            'idProduto' => trim($conteudo['produto']),
                            'datahora' => $ano.'-'.$mes.'-'.$dia,
                        );
                    }
                }
            }
        }
*/
        return $translated;
    }
}
