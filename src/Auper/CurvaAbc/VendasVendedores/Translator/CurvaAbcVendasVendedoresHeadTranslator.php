<?Php
namespace Auper\CurvaAbc\VendasVendedores\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class CurvaAbcVendasVendedoresHeadTranslator extends AbstractTranslator
{

    

    public function translate(array $vendedores)
    {
        foreach ($vendedores['header'] as $classe => $conteudo) {
            $pos++;
           $translated[] = array(
            'ano' => $vendedores['info']['ano'],
            'mes' => $vendedores['info']['mes'],
            'classe' => $classe,
            'intervalo' => $vendedores['info']['int'],
            'valorTotal' => $conteudo['valorTotal'],
            'qtdItens' => $conteudo['qtdItens'],
            'qtdItensDistintos' => $conteudo['qtdItensDistintos'],
            );
        }


        return $translated;
    }
}
