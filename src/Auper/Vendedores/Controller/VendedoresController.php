<?Php

namespace Auper\Vendedores\Controller;

//use Auper\Vendedores\Model\VendedoresModel;
use Silex\Application;
use Auper\Vendedores\DataMapper\VendedoresDataMapper;
use Auper\Vendedores\Translator\ExternalAuperVendedoresVendasTranslator;
use Auper\Vendedores\DataMapper\VendedoresAuperDataMapper;
use Auper\Vendedores\DataMapper\ExternalVendedoresDataMapper;
use Auper\Vendedores\Translator\ExternalVendedoresTranslator;

use Auper\CurvaAbc\VendasVendedores\DataMapper\CurvaAbcVendasVendedoresDataMapper;

class VendedoresController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    //public function

    

    public function importVendedores($dataInicio)
    {
            $dm = new ExternalVendedoresDataMapper($this->app['db']);
            $vendedores = $dm->loadVendedores();

            $tr = new ExternalVendedoresTranslator();
            $translated = $tr->translate($vendedores);

            $dm2 = new VendedoresDataMapper($this->app['db']);
            $dm2->insert($translated);

            return $this->app->json($translated);

    }


    public function getCurvaAbc($dataInicio, $dataFim, $abc, $limit)
    {
        $auperVendedoresMapper = new VendedoresAuperDataMapper($this->app['db']);
        $return = $auperVendedoresMapper->loadAbc($dataInicio, $dataFim);

        $abc = new \Core\Utils\CurvaAbc($abc);
        $abc->setLimit($limit);
        foreach ($return as $vendedor) {
            //print_r($vendedor);die;
            $abc->addLinha($vendedor['produto'], $vendedor['sumQtd'], ($vendedor['sumValor'] / $vendedor['sumQtd']));
        }

        $table = $abc->getTable();
        $arr['data'] = $table;
        return $this->app->json($arr);
    }

    public function getVendasMensalFor($idVendedor, $ano, $qtdValores)
    {
        $arrDom = array(
            'Valores' => 'valorTotal',
            'Quantidades' => 'qtdTotal',
            );
        
        $indice = $arrDom[$qtdValores];
        $dm = new VendedoresDataMapper($this->app['db']);
        $ret = $dm->loadVendasForVendedor($idVendedor, $ano);

        foreach ($ret as $item) {
            $arr[] = array($item['mes'],$item[$indice]);
        }
        return $this->app->json($arr);
    }

     public function getResumo($idVendedor, $ano = false, $mes = false, $intervalo= false)
    {

        if($intervalo == false){
            $intervalo = 3;
        }
        $dm = new VendedoresDataMapper($this->app['db']);
        $ret['vendedor'] = $dm->loadById($idVendedor);

        $dm3 = new CurvaAbcVendasVendedoresDataMapper($this->app['db']);
        $ret['classe'] = $dm3->loadClasseHistorico($idVendedor, $intervalo);
        $ret['vendedor']['classe'] = $ret['classe'][0]['class'];
        $ret['vendedor']['posicao'] = $ret['classe'][0]['pos'];

        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosVendedoresDataMapper($this->app['db']);
        $ret['vendedor']['produtosDistintos'] = $dm->loadProdutosDistintos($idVendedor, $ano, $mes);
        $ret['vendedor']['qtdVendida'] = $dm->loadQtdVendidaForVendedor($idVendedor, $ano, $mes);

        return $this->app->json($ret);

    }

    public function getHistoricoValoresCompra($idCliente, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresClientesDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresCompra($idCliente, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['sumValor']  / $tupla['sumQtd']),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getHistoricoValoresVenda($idVendedor, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresVendedoresDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresVenda($idVendedor, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['sumValor']  / $tupla['sumQtd']),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getHistoricoValoresMargem($idVendedor, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresVendedoresDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresMargem($idVendedor, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['avgMargem']*100),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getClientesForVendedor($idVendedor, $ano = false, $mes = false, $intervalo = false)
    {

        if($ano == false || $mes == false){
            $date = new \DateTime(date("Y-m-d"));
        }else{
            $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mes,'01',$ano));
            $date = new \DateTime($ano."-".$mes."-".$ultimoDiaMesAlvo);
        }

        if($intervalo == false){
            $intervalo = 3;
        }

        $dateOp = new \Core\Utils\DateOperation($date);
        
       // $dateOp->subMonth(1);
        $mesStatus = $dateOp->getMonth();
        $anoStatus = $dateOp->getYear();

        $dateOp->subMonth(1);
        $mesAbc = $dateOp->getMonth();
        $anoAbc = $dateOp->getYear();

        //Carregando Clientes
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresClientesVendedoresDataMapper($this->app['db']);
        $ret = $dm->loadClientesForVendedor($idVendedor);

        //Carregando suas classes
        $dmAbc = new \Auper\CurvaAbc\VendasClientes\DataMapper\CurvaAbcVendasClientesDataMapper($this->app['db']);
        $classes = $dmAbc->loadClasses($mesAbc, $anoAbc, $intervalo);

        $classeFetcher = new \Auper\Clientes\Fetcher\ClientesClasseFetcher();
        $classeFetcher->setInfoToFetch($classes);
        $ret = $classeFetcher->fetch($ret);

        //Carregando seus status
        $dmStatus = new \Auper\Clientes\DataMapper\ClientesStatusDataMapper($this->app['db']);
        $status = $dmStatus->loadClientesStatus($mesStatus, $anoStatus, $intervalo);

        $statusFetcher = new \Auper\Clientes\Fetcher\ClientesStatusFetcher();
        $statusFetcher->setInfoToFetch($status);
        $ret = $statusFetcher->fetch($ret);



        return $this->app->json($ret);
    }

    public function getProdutosForVendedor($idVendedor, $ano = false, $mes = false, $intervalo = false)
    {

        if($ano == false || $mes == false){
            $date = new \DateTime(date("Y-m-d"));
        }else{
            $ultimoDiaMesAlvo = date("t", mktime(0,0,0,$mes,'01',$ano));
            $date = new \DateTime($ano."-".$mes."-".$ultimoDiaMesAlvo);
        }

        if($intervalo == false){
            $intervalo = 3;
        }

        $dateOp = new \Core\Utils\DateOperation($date);
        
       // $dateOp->subMonth(1);
        $mesStatus = $dateOp->getMonth();
        $anoStatus = $dateOp->getYear();

        $dateOp->subMonth(1);
        $mesAbc = $dateOp->getMonth();
        $anoAbc = $dateOp->getYear();

        //Carregando Vendedores
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosVendedoresDataMapper($this->app['db']);
        $ret = $dm->loadProdutosForVendedor($idVendedor, $ano, $mes);

        //Carregando suas classes
        $dmAbc = new \Auper\CurvaAbc\VendasProdutos\DataMapper\CurvaAbcVendasProdutosDataMapper($this->app['db']);
        $classes = $dmAbc->loadClasses($mesAbc, $anoAbc, $intervalo);

        $classeFetcher = new \Auper\Produtos\Fetcher\ProdutosClasseFetcher();
        $classeFetcher->setInfoToFetch($classes);
        $ret = $classeFetcher->fetch($ret);

        return $this->app->json($ret);
    }


}
