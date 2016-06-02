<?Php
namespace Auper\Fornecedores\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class FornecedoresStatusTranslator extends AbstractTranslator
{

    public function translate(array $fornecedores)
    {

        $array = $fornecedores['itens'];
        unset($fornecedores['itens']);
        $array += $fornecedores;
        $arr[] = $array; 
        return $arr;
    }

    public function translateToInsert(array $fornecedoresStatus)
    {

    	foreach ($fornecedoresStatus['itens'] as $cs) {
    		$retStatus[] = array(
    			'ano' => $fornecedoresStatus['ano'],
    			'mes' => $fornecedoresStatus['mes'],
    			'intervalo' => $fornecedoresStatus['intervalo'],
    			'idFornecedor' => $cs['idFornecedor'],
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
