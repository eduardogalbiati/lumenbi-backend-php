<?Php
namespace Auper\Clientes\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ClientesStatusTranslator extends AbstractTranslator
{

    public function translate(array $clientes)
    {

        $array = $clientes['itens'];
        unset($clientes['itens']);
        $array += $clientes;
        $arr[] = $array; 
        return $arr;
    }
}
