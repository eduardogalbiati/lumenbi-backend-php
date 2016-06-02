<?Php
namespace Auper\CurvaAbc\VendasVendedores\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class CurvaAbcVendasVendedoresTranslator extends AbstractTranslator
{

    public function translate(array $vendedores)
    {
        foreach ($vendedores['table'] as $vendedor) {
            $pos++;
           $translated[] = array(
            'ano' => $vendedores['info']['ano'],
            'mes' => $vendedores['info']['mes'],
            'intervalo' => $vendedores['info']['int'],
            'idVendedor' => $vendedor['item'],
            'class' => $vendedor['classe'],
            'qtd' => $vendedor['qtd'],
            'valor' => $vendedor['valorTotal'],
            'prcQtd' => $vendedor['prcQtd'],
            'prcValor' => $vendedor['prcValor'],
            'pos' => $pos,

            );
        }


        return $translated;
    }
}
