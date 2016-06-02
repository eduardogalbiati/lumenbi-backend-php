<?Php
namespace Auper\Vendas\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalAuperVendasTranslator extends AbstractTranslator
{

    protected $arrayOrdenado = array();
    protected $arrayQuantidade = array();

    private function addValue($ano, $mes, $dia, $idPed, $valor, $valorCusto, $desc, $lucro)
    {
        $valor = $valor == '' ? '0' : $valor;
        $valor = number_format($valor, 2, '.', '');
        $this->arrayOrdenado[$ano][$mes][$dia]['valor'] += $valor;
        $this->arrayOrdenado[$ano][$mes][$dia]['valorCusto'] += $valorCusto;
        $this->arrayOrdenado[$ano][$mes][$dia]['desc'] = $desc;
        $this->arrayOrdenado[$ano][$mes][$dia]['lucro'] = $lucro;
        $this->arrayQuantidade[$ano][$mes][$dia][$idPed] =1;
    }

    public function translate(array $vendas)
    {
        foreach ($vendas as $venda) {
            $data = $this->explodeDate($venda['dataref']);
            $vlrTotal = ($venda['VlrUnit']*$venda['Qtde']);
            $valorCusto = ($venda['Custo']*$venda['Qtde']);
            //if($venda['VlrDesc'] != 0){
           //     $desc = round(1 - ($vlrTotal / ($vlrTotal+$venda['VlrDesc'])),2);
            //}else{
               $desc = 0;
            //}
            $vlrTotalDesconto = $vlrTotal;// * (100 - $venda['Descom'])/100;
            $this->addValue($data['ano'], $data['mes'], $data['dia'], $venda['idPedido'], $vlrTotalDesconto, $valorCusto, $desc, $venda['lucro']);
        }
        foreach ($this->arrayOrdenado as $ano => $meses) {
            foreach ($meses as $mes => $dias) {
                foreach ($dias as $dia => $conteudo) {
                    $margem = round($conteudo['lucro'],2);//round((($conteudo['valor']-$conteudo['valorCusto']) / $conteudo['valor']),2 );
                    //if($margem < 0){
                   //     var_dump($conteudo);die;
                   // }
                    $translated[] = array(
                        'ano' => $ano,
                        'mes' => $mes,
                        'dia' => $dia,
                        'qtd' => count($this->arrayQuantidade[$ano][$mes][$dia]),
                        'valor' => $conteudo['valor'],
                        'valorCusto' => $conteudo['valorCusto'],
                        'margem' => $margem,
                        'desconto' => $conteudo['desc'],
                        'datahora' => $ano.'-'.$mes.'-'.$dia,

                    );
                }
            }
        }

        return $translated;

    }
}
