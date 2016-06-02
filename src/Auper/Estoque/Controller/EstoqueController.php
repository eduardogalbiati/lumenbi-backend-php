<?Php

namespace Auper\Estoque\Controller;

//use Auper\Estoque\Model\EstoqueModel;
use Silex\Application;
use Auper\Estoque\DataMapper\EstoqueDataMapper;

use Auper\Estoque\Translator\ShEstoqueTranslator;

use Auper\Estoque\DataMapper\EstoqueQtdValoresDataMapper;



class EstoqueController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function importEstoque()
    {

        // Carregando as Vendas //
        $dataMapper = new EstoqueDataMapper($this->app['db']);
        $vendas = $dataMapper->carregaEstoqueAtual();

        // Traduzindo / Inserindo em VendasValores 
        $estoqueTranslator = new ShEstoqueTranslator();
        $translated = $estoqueTranslator->translate($vendas);

        $auperEstoqueMapper = new EstoqueQtdValoresDataMapper($this->app['db']);
        $auperEstoqueMapper->insertEstoque($translated);

       

    }


    public function getAbc(){

        // Carregando as Vendas //
        $dataMapper = new EstoqueDataMapper($this->app['db']);
        $vendas = $dataMapper->carregaEstoqueAtual();

        // Traduzindo / Inserindo em VendasValores 
        $estoqueTranslator = new ShEstoqueTranslator();
        $translated = $estoqueTranslator->translate($vendas);


        $abc = $this->app['curvaAbc'];

        foreach ($translated as $produto) {
            $abc->addLinha($produto['descricao'], $produto['qtd'], $produto['valor'] ,$produto);
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();

        return $this->app->json($arr);
    }

    public function getAbcSemQtd(){

        // Carregando as Vendas //
        $dataMapper = new EstoqueDataMapper($this->app['db']);
        $vendas = $dataMapper->carregaEstoqueAtual();

        // Traduzindo / Inserindo em VendasValores 
        $estoqueTranslator = new ShEstoqueTranslator();
        $translated = $estoqueTranslator->translate($vendas);


        $abc = $this->app['curvaAbc'];

        foreach ($translated as $produto) {
            $abc->addLinha($produto['descricao'], '1', $produto['valor'] ,$produto);
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();

        return $this->app->json($arr);
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
