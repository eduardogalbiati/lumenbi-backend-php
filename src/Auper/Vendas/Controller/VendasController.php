<?Php

namespace Auper\Vendas\Controller;

//use Auper\Vendas\Model\VendasModel;
use Silex\Application;
use Auper\Vendas\DataMapper\VendasDataMapper;
use Auper\Vendas\Translator\ExternalAuperVendasTranslator;
use Auper\Vendas\Translator\ExternalAuperVendasClientesTranslator;
use Auper\Vendas\Translator\ExternalAuperVendasVendedoresTranslator;
use Auper\Vendas\Translator\ExternalAuperVendasProdutosTranslator;
use Auper\Vendas\Translator\ExternalAuperVendasProdutosClientesTranslator;
use Auper\Vendas\Translator\ExternalAuperVendasProdutosVendedoresTranslator;
use Auper\Vendas\Translator\ExternalAuperVendasClientesVendedoresTranslator;

use Auper\Vendas\DataMapper\VendasQtdValoresDataMapper;
use Auper\Vendas\DataMapper\VendasQtdValoresClientesDataMapper;
use Auper\Vendas\DataMapper\VendasQtdValoresVendedoresDataMapper;
use Auper\Vendas\DataMapper\VendasQtdValoresProdutosDataMapper;
use Auper\Vendas\DataMapper\VendasQtdValoresProdutosClientesDataMapper;
use Auper\Vendas\DataMapper\VendasQtdValoresProdutosVendedoresDataMapper;
use Auper\Vendas\DataMapper\VendasQtdValoresClientesVendedoresDataMapper;


class VendasController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function importResumoMensal()
    {

        // Carregando as Vendas //
        $dataMapper = new VendasDataMapper($this->app['db'], $this->app['clientesExcluidos']);
        $vendas = $dataMapper->carregaVendasMensal(new \DateTime(), new \DateTime());

        // Traduzindo / Inserindo em VendasValores 
        $vendasTranslator = new ExternalAuperVendasTranslator();
        $translated = $vendasTranslator->translate($vendas);

        $auperVendasMapper = new VendasQtdValoresDataMapper($this->app['db']);
        $auperVendasMapper->insertVendasMensal($translated);

        // Traduzindo / Inserindo em VendasClientes //
        $clientesTranslator = new ExternalAuperVendasClientesTranslator();
        $translated = $clientesTranslator->translate($vendas);

        $auperClientesMapper = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $auperClientesMapper->insertVendasMensal($translated);

        // Traduzindo / Inserindo em VendasVendedores //
        $vendedoresTranslator = new ExternalAuperVendasVendedoresTranslator();
        $translated = $vendedoresTranslator->translate($vendas);

        $auperClientesMapper = new VendasQtdValoresVendedoresDataMapper($this->app['db']);
        $auperClientesMapper->insertVendasMensal($translated);

          // Traduzindo / Inserindo em VendasProdutos //
        $produtosTranslator = new ExternalAuperVendasProdutosTranslator();
        $translated = $produtosTranslator->translate($vendas);

        $auperClientesMapper = new VendasQtdValoresProdutosDataMapper($this->app['db']);
        $auperClientesMapper->insertVendasMensal($translated);

        // Traduzindo / Inserindo em VendasProdutosClientes //
        $produtosClientesTranslator = new ExternalAuperVendasProdutosClientesTranslator();
        $translated = $produtosClientesTranslator->translate($vendas);

        $auperProdutosClientesMapper = new VendasQtdValoresProdutosClientesDataMapper($this->app['db']);
        $auperProdutosClientesMapper->insertVendasMensal($translated);

        // Traduzindo / Inserindo em VendasProdutosVendedores //
        $produtosVendedoresTranslator = new ExternalAuperVendasProdutosVendedoresTranslator();
        $translated = $produtosVendedoresTranslator->translate($vendas);

        $auperProdutosVendedoresMapper = new VendasQtdValoresProdutosVendedoresDataMapper($this->app['db']);
        $auperProdutosVendedoresMapper->insertVendasMensal($translated);

        // Traduzindo / Inserindo em VendasClientesVendedores //
        $clientesVendedoresTranslator = new ExternalAuperVendasClientesVendedoresTranslator();
        $translated = $clientesVendedoresTranslator->translate($vendas);

        $auperClientesVendedoresMapper = new VendasQtdValoresClientesVendedoresDataMapper($this->app['db']);
        $auperClientesVendedoresMapper->insertVendasMensal($translated);

      


    }

    //------------- VendasValores ---------------//
    public function getResumoVendas()
    {
        $auperVendasMapper = new VendasQtdValoresDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadAll();
        return $this->app->json($return);

    }
    public function getResumoVendasAnual()
    {
        $auperVendasMapper = new VendasQtdValoresDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadAnual();
        return $this->app->json($return);

    }
    public function getResumoVendasMensal($ano)
    {
        $auperVendasMapper = new VendasQtdValoresDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadMensal($ano);
        return $this->app->json($return);

    }
    public function getResumoVendasDiario($mes, $ano)
    {
        $auperVendasMapper = new VendasQtdValoresDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadDiario($mes, $ano);
        return $this->app->json($return);

    }

    public function getResumoVendasPeriodo($dataInicial, $dataFinal, $view)
    {
        $auperVendasMapper = new VendasQtdValoresDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadPeriodo($dataInicial, $dataFinal,$view);
        return $this->app->json($return);

    }


    /*------------- Clientes ---------------*/
public function getResumoVendasClientes()
    {
        $auperVendasMapper = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadAll();
        return $this->app->json($return);

    }
    public function getResumoVendasClientesAnual()
    {
        $auperVendasMapper = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadAnual();
        return $this->app->json($return);

    }
    public function getResumoVendasClientesMensal($ano)
    {
        $auperVendasMapper = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadMensal($ano);
        return $this->app->json($return);

    }
    public function getResumoVendasClientesDiario($mes, $ano)
    {
        $auperVendasMapper = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadDiario($mes, $ano);
        return $this->app->json($return);

    }

    public function getResumoVendasClientesPeriodo($dataInicial, $dataFinal, $view)
    {
        $auperVendasMapper = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperVendasMapper->loadPeriodo($dataInicial, $dataFinal,$view);
        return $this->app->json($return);

    }


   public function getTopClientesTotal()
    {
        $auperClientesMapper = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperClientesMapper->loadTopClientesTotal();
        return $this->app->json($return);
    }

    public function getTopClientesAno($ano)
    {
        $auperClientesMapper = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperClientesMapper->loadTopClientesAno($ano);
        return $this->app->json($return);
    }

    public function getTopClientesMes($mes, $ano)
    {
        $auperClientesMapper = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperClientesMapper->loadTopClientesMes($mes, $ano);
        return $this->app->json($return);
    }

  

    public function getTopVendedoresTotal()
    {
        $auperVendedoresMapper = new VendasQtdValoresVendedoresDataMapper($this->app['db']);
        $return = $auperVendedoresMapper->loadTopVendedoresTotal();
        return $this->app->json($return);
    }

    public function getTopVendedoresAno($ano)
    {
        $auperVendedoresMapper = new VendasQtdValoresVendedoresDataMapper($this->app['db']);
        $return = $auperVendedoresMapper->loadTopVendedoresAno($ano);
        return $this->app->json($return);
    }

    public function getTopVendedoresMes($mes, $ano)
    {
        $auperVendedoresMapper = new VendasQtdValoresVendedoresDataMapper($this->app['db']);
        $return = $auperVendedoresMapper->loadTopVendedoresMes($mes, $ano);
        return $this->app->json($return);
    }
}
