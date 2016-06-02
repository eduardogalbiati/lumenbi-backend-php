<?Php
namespace Auper\Clientes\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ClientesRecuperadosTranslator extends AbstractTranslator
{

    

    public function tableTranslate(array $clientes)
    {

      foreach ($clientes as $cliente) {
          $sumTotal += $cliente['valorTotal'];
          $qtdClientes += 1;
      }

      $ret['table'] = $clientes;
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
            'ultimaData' => $cliente['ultimaData'],
            'idCliente' => $cliente['idCliente'],
            'valorPeriodo' => $cliente['valorTotal'],
            'qtdPeriodo' => $cliente['qtdTotal'],
            'status'=> 'Recuperado',
            );
         
        }
        return $arr;

    }
}
