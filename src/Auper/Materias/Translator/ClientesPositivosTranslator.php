<?Php
namespace Auper\Clientes\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ClientesPositivosTranslator extends AbstractTranslator
{

    

    public function tableTranslate(array $clientes, $meses)
    {

       foreach ($clientes as $cliente) {
          $arr[$cliente['idCliente']]['qtd'] += 1;
          $arr[$cliente['idCliente']]['valorTotal'] += $cliente['valorTotal'];
          $arr[$cliente['idCliente']]['nome'] = $cliente['cliente'];
          $arr[$cliente['idCliente']]['ultimaData'] = $cliente['ultimaData'];
        }
        

        foreach($arr as $cli => $info) {
          $arr2[$info['qtd']][] = $info;
        }
        asort($arr2);
 
        foreach($arr2 as $qtd => $clientes) {
          if($qtd < $meses) {
            break;
          }
          foreach ($clientes as $info) {
            $arr3[] = Array(
              'cliente' => $info['nome'],
              'qtd' => $info['qtd'],
              'ultimaData' => $info['ultimaData'],
              'valorTotal' => $info['valorTotal'],
              'mediaValor' =>round( $info['valorTotal'] / $info['qtd'],2),
              );
            $sumTotal += $info['valorTotal'];
            $qtdClientes += 1;
          }
        }

        $ret['table'] = $arr3;
        $ret['head'] = Array(
          'sumTotal' => $sumTotal,
          'qtdClientes' => $qtdClientes
          );
        return $ret;

    }

    public function translate($clientes, $mesAlvo, $anoAlvo, $intervalo)
    {

        $arr = array();
        foreach ($clientes as $cliente) {
          $arr[] = array(
            'ano'=> $anoAlvo,
            'mes'=> $mesAlvo,
            'intervalo' => $intervalo,
            'idCliente' => $cliente['idCliente'],
            'ultimaData' => $cliente['ultimaData'],
            'status'=> 'Positivo',
            'valorPeriodo' => round($cliente['valorTotal'] / $intervalo,2),
            'qtdPeriodo' => $cliente['qtdTotal'],
            );
         
        }
        return $arr;

    }
}
