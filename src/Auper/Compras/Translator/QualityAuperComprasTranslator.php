<?Php
namespace Auper\Compras\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperComprasTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $idPed, $valor)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia] += $valor;
        $this->arrayQuantidade[$ano][$mes][$dia][$idPed] +=1;
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
                $this->addValue($data['ano'], $data['mes'], $data['dia'], $compra['idCompra'], $valorEntrada);
                 $idCompra = $compra['idCompra'];
            }else{
                //Caso seja apenas itens do mesmo pedido
                $this->addValue($data['ano'], $data['mes'], $data['dia'], $compra['idCompra'], $vlrTotalDesconto);
            }

        }
        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $valor) {
                    $translated[] = array(
                        'ano' => $ano,
                        'mes' => $mes,
                        'dia' => $dia,
                        'qtd' => count($this->arrayQuantidade[$ano][$mes][$dia]),
                        'valor' => $valor,
                        'datahora' => $ano.'-'.$mes.'-'.$dia,

                    );
                }
            }
        }
        
        return $translated;

    }
}
