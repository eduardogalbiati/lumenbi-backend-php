<?Php
namespace Auper\Vendedores\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalVendedoresTranslator extends AbstractTranslator
{

    public function translate(array $vendedores)
    {

      $translated = array();
      
      foreach ($vendedores as $vendedor) {
         $array = Array(
          'idVendedor' => $vendedor['codigo'],
          'nomeVendedor' => trim($vendedor['nome']),
          'equipe' => $vendedor['equipe'],
          'ativo' => $vendedor['ativo']
          );

         $translated[] = $array;
      }

      return $translated;

    }
}
