<?Php
namespace Auper\CurvaAbc\VendasProdutos\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class CurvaAbcVendasProdutosHeadTranslator extends AbstractTranslator
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
