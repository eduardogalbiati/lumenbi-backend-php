<?Php
namespace Auper\Fornecedores\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalFornecedoresTranslator extends AbstractTranslator
{

    public function translate(array $fornecedores)
    {

      $translated = array();

      
      foreach ($fornecedores as $fornecedor) {
         $array = Array(
          'idFornecedor' => $fornecedor['Codigo'],
          'nomeFornecedor' => (( trim($fornecedor['Fantasia']) == '')? trim($fornecedor['Nome']): trim($fornecedor['Fantasia']) ),
          'telefone1' => trim($fornecedor['Fone']),
          'telefone2' => trim($fornecedor['Fone2']),
          'email' => trim($fornecedor['E_mail']),
          'endereco' => trim($fornecedor['Endereco']),
          'cidade' => trim($fornecedor['Cidade']),
          'estado' => trim($fornecedor['Uf']),
          'cep' => str_replace(array('-'),'', trim($fornecedor['Cep']) ),
          'ativo' => $fornecedor['Ativo'],
          'obs' => trim($fornecedor['Obs']),
          );

         $translated[] = $array;
      }
  
      return $translated;

    }
}
