<?Php

namespace Auper\Clientes\Controller;

use Silex\Application;
use Core\Response\ImporterResponse;

use Auper\Clientes\DataMapper\ClientesDataMapper;
use Auper\Clientes\DataMapper\ClientesStatusDataMapper;
use Auper\Clientes\DataMapper\ClientesStatusHeadDataMapper;

use Auper\Clientes\DataMapper\ExternalClientesDataMapper;
use Auper\Clientes\Translator\ExternalClientesTranslator;

use Auper\Clientes\Translator\ClientesStatusTranslator;
use Auper\Clientes\Translator\ClientesStatusHeadTranslator;

use Auper\CurvaAbc\VendasClientes\DataMapper\CurvaAbcVendasClientesDataMapper;


class ClientesController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function importClientesStatus($mesAlvo, $anoAlvo, $intervalo)
    {
        $res = new ImporterResponse();

        try{
            $clientesDM = new ClientesDataMapper($this->app['db']);
            $clientes = $clientesDM->loadClientesForStatus($mesAlvo, $anoAlvo, $intervalo);

            $cliStatus = new \Core\Utils\ClientesStatusGenerator();
            $cliStatus->setParams(array(
                    'mes' => $mesAlvo,
                    'ano' => $anoAlvo,
                    'int' => $intervalo,
                ));

            $clientes =  $cliStatus->generate($clientes);

            $clientesStatusTR = new ClientesStatusHeadTranslator($this->app['db']);
            $translated = $clientesStatusTR->translateToInsert($clientes);
            $clientesStatusDM = new ClientesStatusHeadDataMapper($this->app['db']);
            $clientesStatusDM->insert($translated);

            $clientesStatusTR = new ClientesStatusTranslator($this->app['db']);
            $translated = $clientesStatusTR->translateToInsert($clientes);

            $clientesStatusDM = new ClientesStatusDataMapper($this->app['db']);
            $clientesStatusDM->insert($translated);
        }catch(\Exception $e){
            $res->setStatus(0);
            $res->setData(Array(
            'erro' => $e->getMessage()
            ));
            return $this->app->json($res->getResponse());

        }
        
        $res->setData(Array(
            'records' => count($translated)
            ));

        return $this->app->json($res->getResponse());

    }

    public function importClientes($dataInicio)
    {
        $res = new ImporterResponse();

        try{
            $dm = new ExternalClientesDataMapper($this->app['db']);
            $clientes = $dm->loadClientes();

            $tr = new ExternalClientesTranslator();
            $translated = $tr->translate($clientes);

            $dm2 = new ClientesDataMapper($this->app['db']);
            $dm2->insert($translated);

            //$dm = new ExternalClientesDataMapper($this->app['db']);
            //$clientes = $dm->loadClientesForUpdate($dataInicio);

           // $tr = new ExternalClientesTranslator();
            //$translated = $tr->translate($clientes);

            //$dm2 = new ClientesDataMapper($this->app['db']);
            //$dm2->update($translated);

       }catch(\Exception $e){
            $res->setStatus(0);
            $res->setData(Array(
            'erro' => $e->getMessage()
            ));
            return $this->app->json($res->getResponse());

        }
        
        $res->setData(Array(
            'records' => count($translated)
            ));

        return $this->app->json($res->getResponse());

    }

    // compram a N meses
    public function getClientesPositivos($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ClientesStatusDataMapper($this->app['db']);
        $return = $dm->loadClientesPositivos($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

     public function getClientesRegulares($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ClientesStatusDataMapper($this->app['db']);
        $return = $dm->loadClientesRegulares($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

    //Fizeram a primeira compra só
    public function getClientesNovos($mesAlvo, $anoAlvo, $intervalo)
    {
        
        $dm = new ClientesStatusDataMapper($this->app['db']);
        $return = $dm->loadClientesNovos($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }
    //clientes que não compra a N meses
    public function getClientesNegativos($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ClientesStatusDataMapper($this->app['db']);
        $return = $dm->loadClientesNegativos($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

    //fizeram compra mais fazia N meses que não compravamx
    public function getClientesRecuperados($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ClientesStatusDataMapper($this->app['db']);
        $return = $dm->loadClientesRecuperados($mesAlvo, $anoAlvo, $intervalo);

        return $this->app->json($return);
    }

     public function getTodosClientes($dataInicio, $dataFim, $ativo)
    {
        $dm = new ClientesDataMapper($this->app['db']);
        $return = $dm->loadTodos($dataInicio, $dataFim, $ativo);

        return $this->app->json($return);
    }

    public function getComparativoClientes($mesAlvo, $anoAlvo, $intervalo)
    {
       
        $dm = new ClientesStatusHeadDataMapper($this->app['db']);
        $ret = $dm->loadClientesStatusComparativoHead($mesAlvo, $anoAlvo, $intervalo);

        //calculando as porcentagens
        $arr['table'] = $ret;
        $total = $ret['itens']['total'];
        unset($ret['itens']['total']);

        foreach($ret['itens'] as $tipo => $qtd){
            //if($tipo != 'neg'){
           // $itemPizza = array();
            //$itemPizza['label'] = $array[$tipo];
            //$itemPizza['data'] = round($qtd * 100 / $ret['total']);
            $arr['pizza']['seq'][] = $tipo;
            $arr['pizza']['chart'][] = round($qtd * 100 / $total);
            $arr['pizza']['info'][] = $qtd;
           // }
        }
       

        $arr['pizza']['total'] = $total;
        return $this->app->json($arr);
     
    }
    public function getComparativoHeadClientes($mesAlvo, $anoAlvo, $intervalo)
    {
        $dm = new ClientesStatusHeadDataMapper($this->app['db']);
        $ret = $dm->loadClientesStatusHead($mesAlvo, $anoAlvo, $intervalo);
        return $this->app->json($ret);
     
    }

    public function getVendasMensalFor($idCliente, $ano, $qtdValores)
    {
        $arrDom = array(
            'Valores' => 'valorTotal',
            'Quantidades' => 'qtdTotal',
            );
        
        $indice = $arrDom[$qtdValores];
        $dm = new ClientesDataMapper($this->app['db']);
        $ret = $dm->loadVendasForCliente($idCliente, $ano);

        foreach ($ret as $item) {
            $arr[] = array($item['mes'],$item[$indice]);
        }
        return $this->app->json($arr);
    }

     public function getResumo($idCliente, $ano = false, $mes = false, $intervalo= false)
    {

        if($intervalo == false){
            $intervalo = 3;
        }
        $dm = new ClientesDataMapper($this->app['db']);
        $ret['cliente'] = $dm->loadById($idCliente);

        $dm2 = new ClientesStatusDataMapper($this->app['db']);
        $ret['status'] = $dm2->loadStatusHistorico($idCliente, $intervalo);

        $ret['cliente']['status'] = $ret['status'][0]['idStatus'];
        $dm3 = new CurvaAbcVendasClientesDataMapper($this->app['db']);
        $ret['classe'] = $dm3->loadClasseHistorico($idCliente, $intervalo);
        $ret['cliente']['classe'] = $ret['classe'][0]['class'];
        $ret['cliente']['posicao'] = $ret['classe'][0]['pos'];

        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosClientesDataMapper($this->app['db']);
        $ret['cliente']['clientesDistintos'] = $dm->loadProdutosDistintos($idCliente, $ano, $mes);
        $ret['cliente']['qtdVendida'] = $dm->loadQtdVendidaForCliente($idCliente, $ano, $mes);

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

    public function getHistoricoValoresVenda($idCliente, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresClientesDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresVenda($idCliente, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['sumValor']  / $tupla['sumQtd']),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getHistoricoValoresMargem($idCliente, $ano)
    {
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresClientesDataMapper($this->app['db']);
        $ret = $dm->loadHistoricoValoresMargem($idCliente, $ano);

        foreach($ret as $tupla)
        {
            $arr[] = array(
                $tupla['mes'],
                round(($tupla['avgMargem']*100),2)
                );
        }

        return $this->app->json($arr);

    }

    public function getProdutosForCliente($idCliente, $ano = false, $mes = false, $intervalo = false)
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
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresProdutosClientesDataMapper($this->app['db']);
        $ret = $dm->loadProdutosForCliente($idCliente);

        //Carregando suas classes
        $dmAbc = new \Auper\CurvaAbc\VendasProdutos\DataMapper\CurvaAbcVendasProdutosDataMapper($this->app['db']);
        $classes = $dmAbc->loadClasses($mesAbc, $anoAbc, $intervalo);

        $classeFetcher = new \Auper\Produtos\Fetcher\ProdutosClasseFetcher();
        $classeFetcher->setInfoToFetch($classes);
        $ret = $classeFetcher->fetch($ret);

        //Carregando seus status
        $dmStatus = new \Auper\Produtos\DataMapper\ProdutosStatusDataMapper($this->app['db']);
        $status = $dmStatus->loadProdutosStatus($mesStatus, $anoStatus, $intervalo);

        $statusFetcher = new \Auper\Produtos\Fetcher\ProdutosStatusFetcher();
        $statusFetcher->setInfoToFetch($status);
        $ret = $statusFetcher->fetch($ret);



        return $this->app->json($ret);
    }

    public function getVendedoresForCliente($idCliente, $ano = false, $mes = false, $intervalo = false)
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
        $dm = new \Auper\Vendas\DataMapper\VendasQtdValoresClientesVendedoresDataMapper($this->app['db']);
        $ret = $dm->loadVendedoresForCliente($idCliente, false, false);

        //Carregando suas classes
        $dmAbc = new \Auper\CurvaAbc\VendasVendedores\DataMapper\CurvaAbcVendasVendedoresDataMapper($this->app['db']);
        $classes = $dmAbc->loadClasses($mesAbc, $anoAbc, $intervalo);
var_dump($classes);
        $classeFetcher = new \Auper\Vendedores\Fetcher\VendedoresClasseFetcher();
        $classeFetcher->setInfoToFetch($classes);
        $ret = $classeFetcher->fetch($ret);

        return $this->app->json($ret);
    }

    
}
