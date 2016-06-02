<?Php
namespace Auper\Compras\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\QtdValoresTranslator;

class ExternalAuperComprasProdutosFornecedoresTranslator extends QtdValoresTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $qtd, $nomeProduto, $valor, $idFornecedor)
    {
        $valor = $valor == '' ? '0' : $valor;
       
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto][$idFornecedor]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto][$idFornecedor]['produto'] = $nomeProduto;
        $this->arrayOrdenado[$ano][$mes][$dia][$nomeProduto][$idFornecedor]['qtd'] += $qtd;

    }

    public function translate(array $compras)
    {
        foreach ($compras as $compra) {

            
            $data = $this->explodeDate($compra['emissao']);
            $vlrTotal = ($compra['total'] + $compra['vlripi'] + $compra['valoricmsst']);
            $vlrTotalDesconto = $vlrTotal;// * (100 - $compra['pdesc'])/100;
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $compra['qtde'], $compra['idProduto'], $vlrTotalDesconto, $compra['idFornecedor']);
        }


        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $produtos) {
                    foreach ($produtos as $produto => $fornecedores) {
                        foreach ($fornecedores as $idFornecedor => $conteudo) {
                            $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => $conteudo['qtd'],
                            'valor' => $conteudo['valor'],
                            'idProduto' => $produto,
                            'idFornecedor' => $idFornecedor,
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
