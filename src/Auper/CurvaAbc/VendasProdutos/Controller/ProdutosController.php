<?Php

namespace Auper\CurvaAbc\VendasProdutos\Controller;

use Silex\Application;

use Auper\Vendas\DataMapper\VendasQtdValoresProdutosDataMapper;

use Auper\CurvaAbc\VendasProdutos\Translator\CurvaAbcVendasProdutosTranslator;
use Auper\CurvaAbc\VendasProdutos\Translator\CurvaAbcVendasProdutosHeadTranslator;

use Auper\CurvaAbc\VendasProdutos\DataMapper\CurvaAbcVendasProdutosDataMapper;
use Auper\CurvaAbc\VendasProdutos\DataMapper\CurvaAbcVendasProdutosHeadDataMapper;

class ProdutosController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function importCurvaAbc($mes, $ano, $int)
    {
        $vqvpd = new VendasQtdValoresProdutosDataMapper($this->app['db']);
        $return = $vqvpd->loadAbc($mes, $ano, $int);

        $abc = $this->app['curvaAbc'];

        foreach ($return as $produto) {
            $abc->addLinha($produto['idProduto'], $produto['sumQtd'], ($produto['sumValor'] / $produto['sumQtd']),$produto);
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();
        $arr['info']['mes'] = $mes;
        $arr['info']['ano'] = $ano; 
        $arr['info']['int'] = $int; 


        $translator = new CurvaAbcVendasProdutosTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcVendasProdutosDataMapper($this->app['db']);
        $dataMapper->insert($result);

        $translator = new CurvaAbcVendasProdutosHeadTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcVendasProdutosHeadDataMapper($this->app['db']);
        $dataMapper->insert($result);

        return $this->app->json($arr);
    }

    

   public function loadCurvaAbcProdutos($mes, $ano, $int, $history = false)
    {
        $vqvpd = new VendasQtdValoresProdutosDataMapper($this->app['db']);
        $produtos = $vqvpd->loadAbc($mes, $ano, $int);


        $abc = $this->app['curvaAbc'];

        if($history === true){

            $dm = new CurvaAbcVendasProdutosDataMapper($this->app['db']);
            $historico = $dm->loadHistoricoAntesDe($mes, $ano, $int);
            $abc->addHistorico($historico);
        }

        $statusDm = new \Auper\Produtos\DataMapper\ProdutosStatusDataMapper($this->app['db']);
        $prodStatus = $statusDm->loadProdutosStatus($mes, $ano, $int);

        $prodFetcher = new \Auper\Produtos\Fetcher\ProdutosStatusFetcher();
        $prodFetcher->setInfoToFetch($prodStatus);
        $produtos = $prodFetcher->fetch($produtos);

        $statusDm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosDataMapper($this->app['db']);
        $prodStatus = $statusDm->loadHistoricoValoresMargemDesde($mes, $ano, $int);

        $prodFetcher = new \Auper\Produtos\Fetcher\ProdutosFaturamentoFetcher();
        $prodFetcher->setInfoToFetch($prodStatus);
        $produtos = $prodFetcher->fetch($produtos);
   
        foreach ($produtos as $produto) {
            $abc->addLinha($produto['nomeProduto'], $produto['sumQtd'], ($produto['sumValor'] / $produto['sumQtd']), $produto);
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

        $dataMapper = new CurvaAbcVendasProdutosHeadDataMapper($this->app['db']);
        $head = $dataMapper->loadHead($mes, $ano, $int);
        return $this->app->json($head);

    }



}
