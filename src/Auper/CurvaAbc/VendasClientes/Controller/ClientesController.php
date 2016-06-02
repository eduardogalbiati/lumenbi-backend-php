<?Php

namespace Auper\CurvaAbc\VendasClientes\Controller;

use Silex\Application;

use Auper\Vendas\DataMapper\VendasQtdValoresClientesDataMapper;

use Auper\CurvaAbc\VendasClientes\Translator\CurvaAbcVendasClientesTranslator;
use Auper\CurvaAbc\VendasClientes\Translator\CurvaAbcVendasClientesHeadTranslator;

use Auper\CurvaAbc\VendasClientes\DataMapper\CurvaAbcVendasClientesDataMapper;
use Auper\CurvaAbc\VendasClientes\DataMapper\CurvaAbcVendasClientesHeadDataMapper;

class ClientesController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function importCurvaAbc($mes, $ano, $int)
    {
        $vqvpd = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $return = $vqvpd->loadAbc($mes, $ano, $int);

        $abc = $this->app['curvaAbc'];

        foreach ($return as $cliente) {
            $abc->addLinha($cliente['idCliente'], $cliente['sumQtd'], ($cliente['sumValor'] / $cliente['sumQtd']), $cliente);
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();
        $arr['info']['mes'] = $mes;
        $arr['info']['ano'] = $ano; 
        $arr['info']['int'] = $int; 


        $translator = new CurvaAbcVendasClientesTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcVendasClientesDataMapper($this->app['db']);
        $dataMapper->insert($result);

        $translator = new CurvaAbcVendasClientesHeadTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcVendasClientesHeadDataMapper($this->app['db']);
        $dataMapper->insert($result);

        return $this->app->json($arr);
    }

    

    public function loadCurvaAbcClientes($mes, $ano, $int, $history = false)
    {
        $vqvpd = new VendasQtdValoresClientesDataMapper($this->app['db']);
        $clientes = $vqvpd->loadAbc($mes, $ano, $int);


        $abc = $this->app['curvaAbc'];

        if($history === true){

            $dm = new CurvaAbcVendasClientesDataMapper($this->app['db']);
            $historico = $dm->loadHistoricoAntesDe($mes, $ano, $int);
            $abc->addHistorico($historico);
        }

        $statusDm = new \Auper\Clientes\DataMapper\ClientesStatusDataMapper($this->app['db']);
        $cliStatus = $statusDm->loadClientesStatus($mes, $ano, $int);

        $clFetcher = new \Auper\Clientes\Fetcher\ClientesStatusFetcher();
        $clFetcher->setInfoToFetch($cliStatus);
        $clientes = $clFetcher->fetch($clientes);
   
        foreach ($clientes as $cliente) {
            $abc->addLinha($cliente['nomeCliente'], $cliente['sumQtd'], ($cliente['sumValor'] / $cliente['sumQtd']), $cliente);
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();
        $arr['info']['mes'] = $mes;
        $arr['info']['ano'] = $ano; 

        return $this->app->json($arr);
    }

    public function loadCurvaAbcClientesHead($mes, $ano, $int)
    {

        $dataMapper = new CurvaAbcVendasClientesHeadDataMapper($this->app['db']);
        $head = $dataMapper->loadHead($mes, $ano, $int);
        return $this->app->json($head);

    }

   



}
