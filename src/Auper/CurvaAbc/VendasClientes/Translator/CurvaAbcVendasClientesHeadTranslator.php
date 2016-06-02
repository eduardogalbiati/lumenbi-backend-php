<?Php
namespace Auper\CurvaAbc\VendasClientes\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class CurvaAbcVendasClientesHeadTranslator extends AbstractTranslator
{

    

    public function translate(array $clientes)
    {
        foreach ($clientes['header'] as $classe => $conteudo) {
            $pos++;
           $translated[] = array(
            'ano' => $clientes['info']['ano'],
            'mes' => $clientes['info']['mes'],
            'classe' => $classe,
            'intervalo' => $clientes['info']['int'],
            'valorTotal' => $conteudo['valorTotal'],
            'qtdItens' => $conteudo['qtdItens'],
            'qtdItensDistintos' => $conteudo['qtdItensDistintos'],
            );
        }


        return $translated;
    }
}
