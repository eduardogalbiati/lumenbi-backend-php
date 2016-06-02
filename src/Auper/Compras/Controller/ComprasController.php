<?Php

namespace Auper\Compras\Controller;

//use Auper\Compras\Model\ComprasModel;
use Silex\Application;
use Auper\Compras\DataMapper\ComprasDataMapper;
use Auper\Compras\Translator\ExternalAuperComprasTranslator;
use Auper\Compras\Translator\ExternalAuperComprasFornecedoresTranslator;
use Auper\Compras\Translator\ExternalAuperComprasVendedoresTranslator;
use Auper\Compras\Translator\ExternalAuperComprasProdutosTranslator;
use Auper\Compras\Translator\ExternalAuperComprasProdutosFornecedoresTranslator;
use Auper\Compras\DataMapper\ComprasQtdValoresDataMapper;
use Auper\Compras\DataMapper\ComprasQtdValoresFornecedoresDataMapper;
use Auper\Compras\DataMapper\ComprasQtdValoresVendedoresDataMapper;
use Auper\Compras\DataMapper\ComprasQtdValoresProdutosDataMapper;
use Auper\Compras\DataMapper\ComprasQtdValoresProdutosFornecedoresDataMapper;

class ComprasController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function importResumoMensal()
    {

        /* Carregando as Compras */
        $dataMapper = new ComprasDataMapper($this->app['db'], $this->app['fornecedoresExcluidos']);
        $compras = $dataMapper->carregaComprasMensal(new \DateTime(), new \DateTime());

        // Traduzindo / Inserindo em ComprasValores //
        $comprasTranslator = new ExternalAuperComprasTranslator();
        $translated = $comprasTranslator->translate($compras);

        $auperComprasMapper = new ComprasQtdValoresDataMapper($this->app['db']);
        $auperComprasMapper->insertComprasMensal($translated);

        // Traduzindo / Inserindo em ComprasFornecedores //
        $FornecedoresTranslator = new ExternalAuperComprasFornecedoresTranslator();
        $translated = $FornecedoresTranslator->translate($compras);

        $auperFornecedoresMapper = new ComprasQtdValoresFornecedoresDataMapper($this->app['db']);
        $auperFornecedoresMapper->insertComprasMensal($translated);

        // Traduzindo / Inserindo em ComprasProdutos //
        $ProdutosTranslator = new ExternalAuperComprasProdutosTranslator();
        $translated = $ProdutosTranslator->translate($compras);

        $auperFornecedoresMapper = new ComprasQtdValoresProdutosDataMapper($this->app['db']);
        $auperFornecedoresMapper->insertComprasMensal($translated);

          // Traduzindo / Inserindo em ComprasProdutosFornecedores //
        $produtosFornecedoresTranslator = new ExternalAuperComprasProdutosFornecedoresTranslator();
        $translated = $produtosFornecedoresTranslator->translate($compras);

        $auperProdutosFornecedoresMapper = new ComprasQtdValoresProdutosFornecedoresDataMapper($this->app['db']);
        $auperProdutosFornecedoresMapper->insertVendasMensal($translated);


    }

    /*------------- ComprasValores ---------------*/
    public function getResumoCompras()
    {
        $auperComprasMapper = new ComprasQtdValoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadAll();
        return $this->app->json($return);

    }
    public function getResumoComprasAnual()
    {
        $auperComprasMapper = new ComprasQtdValoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadAnual();
        return $this->app->json($return);

    }
    public function getResumoComprasMensal($ano)
    {
        $auperComprasMapper = new ComprasQtdValoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadMensal($ano);
        return $this->app->json($return);

    }
    public function getResumoComprasDiario($mes, $ano)
    {
        $auperComprasMapper = new ComprasQtdValoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadDiario($mes, $ano);
        return $this->app->json($return);

    }

    public function getResumoComprasPeriodo($dataInicial, $dataFinal, $view)
    {
        $auperComprasMapper = new ComprasQtdValoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadPeriodo($dataInicial, $dataFinal,$view);
        return $this->app->json($return);

    }


    /*------------- Fornecedores ---------------*/
public function getResumoComprasFornecedores()
    {
        $auperComprasMapper = new ComprasQtdValoresFornecedoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadAll();
        return $this->app->json($return);

    }
    public function getResumoComprasFornecedoresAnual()
    {
        $auperComprasMapper = new ComprasQtdValoresFornecedoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadAnual();
        return $this->app->json($return);

    }
    public function getResumoComprasFornecedoresMensal($ano)
    {
        $auperComprasMapper = new ComprasQtdValoresFornecedoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadMensal($ano);
        return $this->app->json($return);

    }
    public function getResumoComprasFornecedoresDiario($mes, $ano)
    {
        $auperComprasMapper = new ComprasQtdValoresFornecedoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadDiario($mes, $ano);
        return $this->app->json($return);

    }

    public function getResumoComprasFornecedoresPeriodo($dataInicial, $dataFinal, $view)
    {
        $auperComprasMapper = new ComprasQtdValoresFornecedoresDataMapper($this->app['db']);
        $return = $auperComprasMapper->loadPeriodo($dataInicial, $dataFinal,$view);
        return $this->app->json($return);

    }

}
