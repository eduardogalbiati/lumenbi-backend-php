<?Php
namespace Auper\Fornecedores\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class FornecedoresStatusHeadTranslator extends AbstractTranslator
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

        $arr[] = array(
            'ano' => $fornecedoresStatus['ano'],
            'mes' => $fornecedoresStatus['mes'],
            'intervalo' => $fornecedoresStatus['intervalo'],
            'pos' => (($fornecedoresStatus['resumo']['nPos']=='')?'0':$fornecedoresStatus['resumo']['nPos']),
            'neg' => (($fornecedoresStatus['resumo']['nNeg']=='')?'0':$fornecedoresStatus['resumo']['nNeg']),
            'rec' => (($fornecedoresStatus['resumo']['nRec']=='')?'0':$fornecedoresStatus['resumo']['nRec']),
            'nov' => (($fornecedoresStatus['resumo']['nNov']=='')?'0':$fornecedoresStatus['resumo']['nNov']),
            'reg' => (($fornecedoresStatus['resumo']['nReg']=='')?'0':$fornecedoresStatus['resumo']['nReg']),
            'total' => ($fornecedoresStatus['resumo']['nPos'] + $fornecedoresStatus['resumo']['nNeg'] + $fornecedoresStatus['resumo']['nRec']+ $fornecedoresStatus['resumo']['nNov'] + $fornecedoresStatus['resumo']['nReg'] ),
            );

        return $arr;
    }
}
