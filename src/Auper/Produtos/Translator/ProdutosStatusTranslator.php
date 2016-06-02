<?Php
namespace Auper\Produtos\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ProdutosStatusTranslator extends AbstractTranslator
{

    public function translate(array $produtos)
    {

        $array = $produtos['itens'];
        unset($produtos['itens']);
        $array += $produtos;
        $arr[] = $array; 
        return $arr;
    }

    public function translateToInsert(array $produtosStatus)
    {

    	foreach ($produtosStatus['itens'] as $cs) {
    		$retStatus[] = array(
    			'ano' => $produtosStatus['ano'],
    			'mes' => $produtosStatus['mes'],
    			'intervalo' => $produtosStatus['intervalo'],
    			'idProduto' => $cs['idProduto'],
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
