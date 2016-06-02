<?Php
namespace Auper\Produtos\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ProdutosStatusHeadTranslator extends AbstractTranslator
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

        $arr[] = array(
            'ano' => $produtosStatus['ano'],
            'mes' => $produtosStatus['mes'],
            'intervalo' => $produtosStatus['intervalo'],
            'pos' => (($produtosStatus['resumo']['nPos']=='')?'0':$produtosStatus['resumo']['nPos']),
            'neg' => (($produtosStatus['resumo']['nNeg']=='')?'0':$produtosStatus['resumo']['nNeg']),
            'rec' => (($produtosStatus['resumo']['nRec']=='')?'0':$produtosStatus['resumo']['nRec']),
            'nov' => (($produtosStatus['resumo']['nNov']=='')?'0':$produtosStatus['resumo']['nNov']),
            'reg' => (($produtosStatus['resumo']['nReg']=='')?'0':$produtosStatus['resumo']['nReg']),
            'total' => ($produtosStatus['resumo']['nPos'] + $produtosStatus['resumo']['nNeg'] + $produtosStatus['resumo']['nRec']+ $produtosStatus['resumo']['nNov'] + $produtosStatus['resumo']['nReg'] ),
            );

        return $arr;
    }
}
