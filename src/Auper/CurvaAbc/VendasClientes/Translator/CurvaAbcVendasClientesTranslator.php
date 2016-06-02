<?Php
namespace Auper\CurvaAbc\VendasClientes\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class CurvaAbcVendasClientesTranslator extends AbstractTranslator
{

    public function translate(array $clientes)
    {
        foreach ($clientes['table'] as $cliente) {
            $pos++;
           $translated[] = array(
            'ano' => $clientes['info']['ano'],
            'mes' => $clientes['info']['mes'],
            'intervalo' => $clientes['info']['int'],
            'idCliente' => $cliente['item'],
            'class' => $cliente['classe'],
            'qtd' => $cliente['qtd'],
            'valor' => $cliente['valorTotal'],
            'prcQtd' => $cliente['prcQtd'],
            'prcValor' => $cliente['prcValor'],
            'pos' => $pos,

            );
        }


        return $translated;
    }
}
