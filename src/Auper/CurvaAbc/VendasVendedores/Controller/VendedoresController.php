<?Php

namespace Auper\CurvaAbc\VendasVendedores\Controller;

use Silex\Application;

use Auper\Vendas\DataMapper\VendasQtdValoresVendedoresDataMapper;

use Auper\CurvaAbc\VendasVendedores\Translator\CurvaAbcVendasVendedoresTranslator;
use Auper\CurvaAbc\VendasVendedores\Translator\CurvaAbcVendasVendedoresHeadTranslator;

use Auper\CurvaAbc\VendasVendedores\DataMapper\CurvaAbcVendasVendedoresDataMapper;
use Auper\CurvaAbc\VendasVendedores\DataMapper\CurvaAbcVendasVendedoresHeadDataMapper;

class VendedoresController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function importCurvaAbc($mes, $ano, $int)
    {

        $vqvpd = new VendasQtdValoresVendedoresDataMapper($this->app['db']);
        $return = $vqvpd->loadAbc($mes, $ano, $int);
var_dump($return);
        $abc = $this->app['curvaAbc'];

        foreach ($return as $vendedor) {
            $abc->addLinha($vendedor['idVendedor'], $vendedor['sumQtd'], ($vendedor['sumValor'] / $vendedor['sumQtd']), $vendedor);
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();
        $arr['info']['mes'] = $mes;
        $arr['info']['ano'] = $ano; 
        $arr['info']['int'] = $int; 


        $translator = new CurvaAbcVendasVendedoresTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcVendasVendedoresDataMapper($this->app['db']);
        $dataMapper->insert($result);

        $translator = new CurvaAbcVendasVendedoresHeadTranslator();
        $result =$translator->translate($arr);

        $dataMapper = new CurvaAbcVendasVendedoresHeadDataMapper($this->app['db']);
        $dataMapper->insert($result);

        return $this->app->json($arr);
    }

    

    public function loadCurvaAbcVendedores($mes, $ano, $int, $history = false)
    {
        $vqvpd = new VendasQtdValoresVendedoresDataMapper($this->app['db']);
        $vendedores = $vqvpd->loadAbc($mes, $ano, $int);


        $abc = $this->app['curvaAbc'];

        if($history === true){

            $dm = new CurvaAbcVendasVendedoresDataMapper($this->app['db']);
            $historico = $dm->loadHistoricoAntesDe($mes, $ano, $int);
            $abc->addHistorico($historico);
        }

   
        foreach ($vendedores as $vendedor) {
            $abc->addLinha($vendedor['nomeVendedor'], $vendedor['sumQtd'], ($vendedor['sumValor'] / $vendedor['sumQtd']), $vendedor);
        }

        $table = $abc->getTable();

        $arr['table'] = $table;
        $arr['header'] = $abc->getHeader();
        $arr['info']['mes'] = $mes;
        $arr['info']['ano'] = $ano; 

        return $this->app->json($arr);
    }

    public function loadCurvaAbcVendedoresHead($mes, $ano, $int)
    {

        $dataMapper = new CurvaAbcVendasVendedoresHeadDataMapper($this->app['db']);
        $head = $dataMapper->loadHead($mes, $ano, $int);
        return $this->app->json($head);

    }



}
