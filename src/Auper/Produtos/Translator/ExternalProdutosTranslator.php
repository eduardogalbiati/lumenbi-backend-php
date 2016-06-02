<?Php
namespace Auper\Produtos\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalProdutosTranslator extends AbstractTranslator
{

    public function translate(array $produtos)
    {

      $translated = array();
      
      foreach ($produtos as $produto) {
         $array = Array(
          'idProduto' => $produto['codigo'],
          'nomeProduto' => trim($produto['descricao']),
          'dataHoraUltCompra' => $produto['ultcompra'],
          'dataHoraCad' => $produto['dtcad']
          );

         $translated[] = $array;
      }

      return $translated;

    }
}
