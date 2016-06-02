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

    public function translateToInsert(array $clientesStatus)
    {

    	foreach ($clientesStatus['itens'] as $cs) {
    		$retStatus[] = array(
    			'ano' => $clientesStatus['ano'],
    			'mes' => $clientesStatus['mes'],
    			'intervalo' => $clientesStatus['intervalo'],
    			'idCliente' => $cs['idCliente'],
    			'idStatus' => $cs['idStatus'],
    			'periodoStatus' => $cs['periodoStatus'],
    			'ultimaData' => $cs['ultimaData'],
    			'valorPeriodo' => $cs['valorPeriodo'],
    			'qtdPeriodo' => $cs['qtdPeriodo'],
    			);
    	}

    	return $retStatus;
    }
}
