<?Php
namespace Auper\CurvaAbc\ComprasProdutos\Translator;

use Doctrine\DBAL\Connection;
use Core\Utils\Translator\AbstractTranslator;

class CurvaAbcComprasProdutosTranslator extends AbstractTranslator
{

    public function translate(array $produtos)
    {
        foreach ($produtos['table'] as $produto) {
            $pos++;
           $translated[] = array(
            'ano' => $produtos['info']['ano'],
            'mes' => $produtos['info']['mes'],
            'intervalo' => $produtos['info']['int'],
            'idProduto' => $produto['item'],
            'class' => $produto['classe'],
            'qtd' => $produto['qtd'],
            'valor' => $produto['valorTotal'],
            'prcQtd' => $produto['prcQtd'],
            'prcValor' => $produto['prcValor'],
            'pos' => $pos,

            );
        }


        return $translated;
    }
}
