<?Php
namespace Auper\Clientes\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalClientesTranslator extends AbstractTranslator
{

    public function translate(array $clientes)
    {

      $translated = array();
      $arr = array('-','(');
      foreach ($clientes as $cliente) {
         $array = Array(
          'idCliente' => $cliente['codigo'],
          'nomeCliente' => (( trim($cliente['fantasia']) == '')? trim($cliente['nome']): trim($cliente['fantasia']) ),
          'cep' => str_replace($arr, '', trim($cliente['cep'])),
          'uf' => trim($cliente['uf']),
          'endereco' => trim($cliente['endereco']),
          'bairro' => trim($cliente['bairro']),
          'cidade' => trim($cliente['cidade']),
          'telefone1' => $cliente['fone'],
          'motivoBloqueio' => $cliente['motivobloqueio1'],
          'dataHoraUltCompra' => $cliente['ultcompra'],
          'dataHoraCad' => $cliente['dtinc'],
          'status' => $cliente['status'],
          'ativo' => $cliente['ativo'],
          );

         $translated[] = $array;
      }

      return $translated;

    }
}
