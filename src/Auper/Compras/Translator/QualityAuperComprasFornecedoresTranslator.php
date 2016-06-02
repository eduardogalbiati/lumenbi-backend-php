<?Php
namespace Auper\Compras\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperComprasFornecedoresTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $idPedido, $valor, $fornecedor)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia][$fornecedor]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia][$fornecedor]['fornecedor'] = $fornecedor;
        $this->arrayQuantidade[$ano][$mes][$dia][$fornecedor][$idPedido] += 1;
    }

    public function translate(array $compras)
    {
        $idCompra = '';
        foreach ($compras as $compra) {
            $data = $this->explodeDate($compra['emissao']);
            $vlrTotal = ($compra['total'] + $compra['vlripi'] + $compra['valoricmsst']);
            $vlrTotalDesconto = $vlrTotal;// * (100 - $compra['pdesc'])/100;

            //caso seja a primeira linha do pedido
            if($compra['idCompra'] != $idCompra){
                $valorEntrada = $vlrTotal + ($compra['vlroutros'] + $compra['vlrfrete']);
                $this->addValue($data['ano'], $data['mes'], $data['dia'], $compra['idCompra'], $valorEntrada, trim($compra['idFornecedor']));
                $idCompra = $compra['idCompra'];
            }else{
                //Caso seja apenas itens do mesmo pedido
                $this->addValue($data['ano'], $data['mes'], $data['dia'], $compra['idCompra'], $vlrTotalDesconto, trim($compra['idFornecedor']));
            }

           
           // $this->addValue($data['ano'], $data['mes'], $data['dia'], $compra['idCompra'], $vlrTotalDesconto, trim($compra['idFornecedor']));

        }

        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $fornecedores) {
                    foreach ($fornecedores as $fornecedor => $conteudo) {
                        $translated[] = array(
                            'ano' => $ano,
                            'mes' => $mes,
                            'dia' => $dia,
                            'qtd' => count($this->arrayQuantidade[$ano][$mes][$dia][$fornecedor]),
                            'valor' => $conteudo['valor'],
                            'idFornecedor' => trim($conteudo['fornecedor']),
                            'datahora' => $ano.'-'.$mes.'-'.$dia,
                        );
                    }
                }
            }
        }

        return $translated;
    }
}
