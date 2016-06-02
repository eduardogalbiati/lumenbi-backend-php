<?Php
namespace Auper\CurvaAbc\ComprasProdutos\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class CurvaAbcComprasProdutosHeadTranslator extends AbstractTranslator
{

    

    public function translate(array $produtos)
    {
        foreach ($produtos['header'] as $classe => $conteudo) {
            $pos++;
           $translated[] = array(
            'ano' => $produtos['info']['ano'],
            'mes' => $produtos['info']['mes'],
            'classe' => $classe,
            'intervalo' => $produtos['info']['int'],
            'valorTotal' => $conteudo['valorTotal'],
            'qtdItens' => $conteudo['qtdItens'],
            'qtdItensDistintos' => $conteudo['qtdItensDistintos'],
            );
        }


        return $translated;
    }
}
