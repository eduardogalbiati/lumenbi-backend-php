<?Php

namespace Auper\CurvaAbc\ComprasProdutos\Controller;

use Silex\Application;

use Auper\Compras\DataMapper\ComprasQtdValoresProdutosDataMapper;

use Auper\CurvaAbc\ComprasProdutos\Translator\CurvaAbcComprasProdutosTranslator;
use Auper\CurvaAbc\ComprasProdutos\Translator\CurvaAbcComprasProdutosHeadTranslator;

use Auper\CurvaAbc\ComprasProdutos\DataMapper\CurvaAbcComprasProdutosDataMapper;
use Auper\CurvaAbc\ComprasProdutos\DataMapper\CurvaAbcComprasProdutosHeadDataMapper;

class ProdutosController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function importCurvaAbc($mes, $ano, $int)
    {
        $vqvpd = new ComprasQtdValoresProdutosDataMapper($this->app['db']);
        $return = $vqvpd->loadAbc($mes, $ano, $int);

        $abc = $this->app['curvaAbc'];

        foreach ($return as $produto) {
            $abc->addLinha($produto['idProduto'], $produto['sumQtd'], ($produto['sumValor'] / $produto['sumQtd']));
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();
        $arr['info']['mes'] = $mes;
        $arr['info']['ano'] = $ano; 
        $arr['info']['int'] = $int; 


        $translator = new CurvaAbcComprasProdutosTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcComprasProdutosDataMapper($this->app['db']);
        $dataMapper->insert($result);

        $translator = new CurvaAbcComprasProdutosHeadTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcComprasProdutosHeadDataMapper($this->app['db']);
        $dataMapper->insert($result);

        return $this->app->json($arr);
    }

    

    public function loadCurvaAbcProdutos($mes, $ano, $int, $history = false)
    {
        $vqvpd = new ComprasQtdValoresProdutosDataMapper($this->app['db']);
        $return = $vqvpd->loadAbc($mes, $ano, $int);


        $abc = $this->app['curvaAbc'];

        if($history === true){

            $dm = new CurvaAbcComprasProdutosDataMapper($this->app['db']);
            $historico = $dm->loadHistoricoAntesDe($mes, $ano, $int);
            $abc->addHistorico($historico);
        }

        foreach ($return as $produto) {
            $abc->addLinha($produto['nomeProduto'], $produto['sumQtd'], ($produto['sumValor'] / $produto['sumQtd']));
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();
        $arr['info']['mes'] = $mes;
        $arr['info']['ano'] = $ano; 

        return $this->app->json($arr);
    }

    public function loadCurvaAbcProdutosHead($mes, $ano, $int)
    {

        $dataMapper = new CurvaAbcComprasProdutosHeadDataMapper($this->app['db']);
        $head = $dataMapper->loadHead($mes, $ano, $int);
        return $this->app->json($head);

    }



}
