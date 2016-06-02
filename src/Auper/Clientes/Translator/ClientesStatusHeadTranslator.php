<?Php
namespace Auper\Clientes\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class ClientesStatusHeadTranslator extends AbstractTranslator
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

        $arr[] = array(
            'ano' => $clientesStatus['ano'],
            'mes' => $clientesStatus['mes'],
            'intervalo' => $clientesStatus['intervalo'],
            'pos' => (($clientesStatus['resumo']['nPos']=='')?'0':$clientesStatus['resumo']['nPos']),
            'neg' => (($clientesStatus['resumo']['nNeg']=='')?'0':$clientesStatus['resumo']['nNeg']),
            'rec' => (($clientesStatus['resumo']['nRec']=='')?'0':$clientesStatus['resumo']['nRec']),
            'nov' => (($clientesStatus['resumo']['nNov']=='')?'0':$clientesStatus['resumo']['nNov']),
            'reg' => (($clientesStatus['resumo']['nReg']=='')?'0':$clientesStatus['resumo']['nReg']),
            'total' => ($clientesStatus['resumo']['nPos'] + $clientesStatus['resumo']['nNeg'] + $clientesStatus['resumo']['nRec']+ $clientesStatus['resumo']['nNov'] + $clientesStatus['resumo']['nReg'] ),
            );

        return $arr;
    }
}
