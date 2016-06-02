<?Php

namespace Auper\Faturamento\Controller;

//use Auper\Faturamento\Model\FaturamentoModel;
use Silex\Application;
use Auper\Faturamento\DataMapper\FaturamentoDataMapper;
use Auper\Faturamento\Translator\ExternalAuperFaturamentoTranslator;
use Auper\Faturamento\Translator\ExternalAuperFaturamentoClientesTranslator;
use Auper\Faturamento\Translator\ExternalAuperFaturamentoVendedoresTranslator;
use Auper\Faturamento\Translator\ExternalAuperFaturamentoProdutosTranslator;
use Auper\Faturamento\DataMapper\FaturamentoQtdValoresDataMapper;
use Auper\Faturamento\DataMapper\FaturamentoQtdValoresClientesDataMapper;
use Auper\Faturamento\DataMapper\FaturamentoQtdValoresVendedoresDataMapper;
use Auper\Faturamento\DataMapper\FaturamentoQtdValoresProdutosDataMapper;

class FaturamentoController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function importResumoMensal()
    {

        /* Carregando as Faturamento */
        $dataMapper = new FaturamentoDataMapper($this->app['db'], $this->app['clientesExcluidos']);
        $Faturamento = $dataMapper->carregaFaturamentoMensal(new \DateTime(), new \DateTime());

        /* Traduzindo / Inserindo em FaturamentoValores */
        $FaturamentoTranslator = new ExternalAuperFaturamentoTranslator();
        $translated = $FaturamentoTranslator->translate($Faturamento);

        $auperFaturamentoMapper = new FaturamentoQtdValoresDataMapper($this->app['db']);
        $auperFaturamentoMapper->insertFaturamentoMensal($translated);

          /* Traduzindo / Inserindo em FaturamentoProdutos */
        $produtosTranslator = new ExternalAuperFaturamentoProdutosTranslator();
        $translated = $produtosTranslator->translate($Faturamento);

        $auperClientesMapper = new FaturamentoQtdValoresProdutosDataMapper($this->app['db']);
        $auperClientesMapper->insertFaturamentoMensal($translated);

         /* Traduzindo / Inserindo em VendasClientes */
        $clientesTranslator = new ExternalAuperFaturamentoClientesTranslator();
        $translated = $clientesTranslator->translate($Faturamento);

        $auperClientesMapper = new FaturamentoQtdValoresClientesDataMapper($this->app['db']);
        $auperClientesMapper->insertFaturamentoMensal($translated);

        /* Traduzindo / Inserindo em FaturamentoVendedores */
        $vendedoresTranslator = new ExternalAuperFaturamentoVendedoresTranslator();
        $translated = $vendedoresTranslator->translate($Faturamento);

        $auperClientesMapper = new FaturamentoQtdValoresVendedoresDataMapper($this->app['db']);
        $auperClientesMapper->insertFaturamentoMensal($translated);

    }

    /*------------- FaturamentoValores ---------------*/
    public function getResumoFaturamento()
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadAll();
        return $this->app->json($return);

    }
    public function getResumoFaturamentoAnual()
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadAnual();
        return $this->app->json($return);

    }
    public function getResumoFaturamentoMensal($ano)
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadMensal($ano);
        return $this->app->json($return);

    }
    public function getResumoFaturamentoDiario($mes, $ano)
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadDiario($mes, $ano);
        return $this->app->json($return);

    }

    public function getResumoFaturamentoPeriodo($dataInicial, $dataFinal, $view)
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadPeriodo($dataInicial, $dataFinal,$view);
        return $this->app->json($return);

    }


    /*------------- Clientes ---------------*/
public function getResumoFaturamentoClientes()
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadAll();
        return $this->app->json($return);

    }
    public function getResumoFaturamentoClientesAnual()
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadAnual();
        return $this->app->json($return);

    }
    public function getResumoFaturamentoClientesMensal($ano)
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadMensal($ano);
        return $this->app->json($return);

    }
    public function getResumoFaturamentoClientesDiario($mes, $ano)
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadDiario($mes, $ano);
        return $this->app->json($return);

    }

    public function getResumoFaturamentoClientesPeriodo($dataInicial, $dataFinal, $view)
    {
        $auperFaturamentoMapper = new FaturamentoQtdValoresClientesDataMapper($this->app['db']);
        $return = $auperFaturamentoMapper->loadPeriodo($dataInicial, $dataFinal,$view);
        return $this->app->json($return);

    }



}
