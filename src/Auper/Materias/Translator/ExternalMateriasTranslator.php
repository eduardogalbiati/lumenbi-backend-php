<?Php
namespace Auper\Materias\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ExternalMateriasTranslator extends AbstractTranslator
{

    public function translate(array $materias)
    {

      $translated = array();

      
      foreach ($materias as $materia) {
         $array = Array(
          'idMateria' => $materia['Codigo'],
          'nomeMateria' =>  trim($materia['Descricao']),
          );

         $translated[] = $array;
      }
  
      return $translated;

    }
}
